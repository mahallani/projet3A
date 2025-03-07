<<<<<<< HEAD
import { Application } from "@hotwired/stimulus";

const app = Application.start();

// Enregistre tes contrôleurs ici si nécessaire
// app.register('some_controller_name', SomeImportedController);

export default app;
=======
import { startStimulusApp } from '@symfony/stimulus-bundle';

const app = startStimulusApp();
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
