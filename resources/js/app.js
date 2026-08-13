import './echo';
import { animate } from 'motion';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import SplitType from 'split-type';
import Lenis from '@studio-freight/lenis';

import Alpine from 'alpinejs';

gsap.registerPlugin(ScrollTrigger);

window.animate = animate;
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;
window.SplitType = SplitType;
window.Lenis = Lenis;
window.Alpine = Alpine;

Alpine.start();
