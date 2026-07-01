/**
 * Gallery lightbox — Fancybox loaded only when gallery markup is present.
 */
import { msrseminarsDomReady } from './dom-ready.js';

msrseminarsDomReady(function () {
	if (!document.querySelector('[data-fancybox="gallery"]')) {
		return;
	}

	Promise.all([
		import('@fancyapps/ui'),
		import('@fancyapps/ui/dist/fancybox.css'),
	]).then(function (modules) {
		var Fancybox = modules[0].Fancybox;
		Fancybox.bind('[data-fancybox="gallery"]', {
			Toolbar: false,
			animated: false,
			dragToClose: false,
			showClass: false,
			hideClass: false,
			closeButton: 'top',
			Image: {
				click: 'close',
				wheel: 'slide',
				zoom: false,
				fit: 'cover',
			},
			Thumbs: {
				minScreenHeight: 0,
			},
		});
	});
});
