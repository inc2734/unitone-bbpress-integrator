document.addEventListener( 'DOMContentLoaded', () => {
	const likeButtons = document.querySelectorAll(
		'.unitone-bbpress-integrator-reply-likes'
	);

	[].slice.call( likeButtons ).forEach( ( likeButton ) => {
		const count = likeButton.querySelector(
			'.unitone-bbpress-integrator-likes__count'
		);
		if ( ! count ) {
			return;
		}

		likeButton.addEventListener( 'click', async ( event ) => {
			event.preventDefault();

			if ( count.textContent.trim().match( /^\d+$/ ) ) {
				count.textContent = parseInt( count.textContent.trim() ) + 1;
			}

			const users =
				likeButton.parentNode.nextElementSibling.querySelector(
					'.unitone-bbpress-integrator-likes-users__users'
				);
			if ( !! users ) {
				const noUserLabel = users.querySelector(
					'.unitone-bbpress-integrator-likes-users__no-user-label'
				);
				if ( !! noUserLabel ) {
					noUserLabel.remove();
				}

				const yetLiked = ! users.querySelector(
					`[data-user-id="${ unitone_bbpress_integrator.currentUser.id }"]`
				);
				if ( yetLiked ) {
					const userHtml = document.createElement( 'div' );
					userHtml.classList.add(
						'unitone-bbpress-integrator-likes-users__user'
					);
					userHtml.setAttribute(
						'data-user-id',
						unitone_bbpress_integrator.currentUser.id
					);

					userHtml.appendChild(
						new DOMParser().parseFromString(
							unitone_bbpress_integrator.currentUser.avatar,
							'text/html'
						).body.firstElementChild
					);

					users.appendChild( userHtml );
				}
			}

			try {
				const response = await fetch(
					unitone_bbpress_integrator.endpoint.like.update,
					{
						method: 'POST',
						body: JSON.stringify( {
							replyId: likeButton.getAttribute( 'data-reply-id' ),
						} ),
						headers: {
							'X-WP-Nonce': unitone_bbpress_integrator.nonce,
						},
					}
				);

				if ( response.ok ) {
					const json = await response.json();
					count.textContent = json.likes;
				} else {
					throw new Error( response.statusText );
				}
			} catch ( error ) {
				console.error( error );
			}
		} );
	} );
} );
