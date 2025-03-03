<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/admin')]
class AdminController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }


    
    #[Route('/pdfuser', name: 'app_userPDF', methods: ['GET'])]
    public function pdf(UserRepository $userRepository): Response
    {
        $html = $this->renderView('admin/userPDF.html.twig', [
            'users' => $userRepository->findAll(),
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename=listUser.pdf ');

        return $response;
    }

    #[Route('/', name: 'app_admin_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $searchQuery = $request->query->get('search');
        $users = $userRepository->searchUsers($searchQuery);
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, EntityManagerInterface $entityManager): Response
    {
        $entityManager = $this->managerRegistry->getManager();
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $entityManager = $this->managerRegistry->getManager();
        $form = $this->createForm(UserType::class, $user);
        $form->remove('plainPassword');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
            }else {
                // Redirect to app_profile
                return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        $entityManager = $this->managerRegistry->getManager();
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route("/admin/verify/{id}", name:"verify_user")]
    public function verifyUserAccount($id): Response
    {
        $entityManager = $this->managerRegistry->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $user->setIsVerified(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_index');
    }

    #[Route('/ban/{id}', name: 'app_admin_banu')]
    public function banUser($id): Response
    {
        $entityManager = $this->managerRegistry->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $user->setIsBanned(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_index');
    }

    #[Route('/unban/{id}', name: 'app_admin_unbanu')]
    public function unbanUser($id): Response
    {
        $entityManager = $this->managerRegistry->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $user->setIsBanned(false);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_index');
    }
}

