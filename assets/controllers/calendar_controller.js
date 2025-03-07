import { Controller } from "@hotwired/stimulus";
import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

export default class extends Controller {
    connect() {
        console.log("📅 Le contrôleur du calendrier est chargé !");
        var calendarEl = this.element;

        fetch("/api/medecin/events") // Récupérer les événements depuis l'API
            .then(response => response.json())
            .then(events => {
                console.log("📌 Événements récupérés :", events);

                var calendar = new Calendar(calendarEl, {
                    plugins: [dayGridPlugin, interactionPlugin],
                    initialView: "dayGridMonth",
                    events: events, // Injecter les événements

                    eventClick: function(info) {
                        info.jsEvent.preventDefault(); // Empêche la redirection

                        console.log("🖱️ Event click detected", info.event);
                        let modal = document.getElementById("eventModal");
                        let modalContent = document.getElementById("modalContent");

                        // Charger le contenu du rendez-vous via AJAX
                        fetch(info.event.url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
                            .then(response => response.text())
                            .then(html => {
                                modalContent.innerHTML = html; // Injecte le contenu AJAX dans le modal
                                modal.style.display = "flex";
                            })
                            .catch(error => console.error("❌ Erreur lors du chargement du modal :", error));
                    }
                });

                calendar.render();
            })
            .catch(error => console.error("❌ Erreur lors du chargement des événements :", error));
    }
}

