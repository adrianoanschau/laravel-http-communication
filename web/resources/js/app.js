import "./bootstrap";
import { Ripple, initTE } from "tw-elements";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();
initTE({ Ripple });
