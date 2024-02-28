import "./bootstrap";
import { Modal, Ripple, initTE } from "tw-elements";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

initTE({ Modal, Ripple });
