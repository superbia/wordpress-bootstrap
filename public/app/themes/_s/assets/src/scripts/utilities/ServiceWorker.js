function register() {
	if ( 'serviceWorker' in navigator ) {
		window.addEventListener( 'load', () => {
			navigator.serviceWorker.register( '/sw.js' );
		} );
	}
}

export default {
	register,
};
