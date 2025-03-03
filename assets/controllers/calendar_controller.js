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
                        console.log("🖱️ Event click detected", info.event);

                        if (info.event.url) {
                            window.location.href = info.event.url; // 🔗 Rediriger vers la page du rendez-vous
                        }
                    }
                });

                calendar.render();
            })
            .catch(error => console.error("❌ Erreur lors du chargement des événements :", error));
    }
}
