import Chart from 'chart.js/auto';
import './bootstrap.js';


/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import { Application } from "@hotwired/stimulus";

// Initialise Stimulus
const app = Application.start();

// Importe les contrôleurs Stimulus manuellement
import CalendarController from "./controllers/calendar_controller";
app.register("calendar", CalendarController);

export default app;
