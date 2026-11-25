<?php
/**
 * Home page sections 7-11, from PRL Home v3.
 *
 * @package parkland
 */

defined( 'ABSPATH' ) || exit;

/* =========================================================
 * 7 - Events. Audience chips + feature card + list.
 * ========================================================= */
function prl_events_data() {
	return apply_filters( 'prl_home_events', array(
		array( true,  'NOV', '23', 'Baby & Me Rhyme Time', '10:30 am', 'Ponoka Jubilee Library', 'Babies & Toddlers', 'prl-event-1' ),
		array( true,  'NOV', '23', 'Lego Club', '3:30 pm', 'Blackfalds Public Library', 'Kids (6 - 12)', 'prl-event-2' ),
		array( true,  'NOV', '23', 'Tech Help Drop-in', '2:00 pm', 'Camrose Public Library', 'Seniors', 'prl-event-3' ),
		array( false, 'NOV', '25', 'Story Time', '10:00 am', 'Innisfail Public Library', 'Kids (2 - 6)', '' ),
		array( false, 'NOV', '27', 'Teen Gaming Night', '6:30 pm', 'Sundre Municipal Library', 'Teens (13 - 17 yrs)', '' ),
		array( false, 'NOV', '28', 'Winter Reads Book Club', '7:00 pm', 'Alix Public Library', 'Adult', '' ),
		array( false, 'DEC', '02', 'Family Craft Afternoon', '1:00 pm', 'Rocky Mountain House Public Library', 'Families', '' ),
		array( false, 'DEC', '04', 'Holiday Movie Matinee', '2:00 pm', 'Castor Municipal Library', 'All Ages', '' ),
	) );
}

function prl_section_events() {
	$auds = array( 'All', 'Adult', 'All Ages', 'Babies & Toddlers', 'Families', 'Kids (2 - 6)', 'Kids (6 - 12)', 'Preteens (9 - 12 yrs)', 'Seniors', 'Teens (13 - 17 yrs)' );
	$events = prl_events_data();
	$feat   = $events[0];
	$rest   = array_slice( $events, 1, 4 ); // design shows four rows beside the feature card
	$fimg   = prl_img( $feat[7], 'large' );
	ob_start();
	?>
	<section class="prl-events" id="events">
		<div class="prl-shell">
			<div class="prl-events__head">
				<div>
					<span class="prl-eyebrow">What's on across Parkland</span>
					<h2 class="prl-events__title">Events</h2>
					<p class="prl-events__sub">View upcoming events at the library.</p>
				</div>
				<div class="prl-events__toggle" data-prl-panels>
					<button type="button" data-prl-tab="all" aria-selected="true">View All</button>
					<button type="button" data-prl-tab="today" aria-selected="false">Today's Events</button>
				</div>
			</div>

			<div class="prl-events__chips" role="group" aria-label="Filter by audience">
				<?php foreach ( $auds as $i => $a ) : ?>
					<button type="button" class="prl-chip<?php echo $i ? '' : ' is-active'; ?>" data-prl-aud="<?php echo esc_attr( $a ); ?>" aria-pressed="<?php echo $i ? 'false' : 'true'; ?>"><?php echo esc_html( $a ); ?></button>
				<?php endforeach; ?>
			</div>

			<div class="prl-events__grid">
				<a class="prl-events__feature" href="/events/"<?php echo $fimg ? ' style="background-image:url(' . esc_url( $fimg ) . ')"' : ''; ?>>
					<span class="prl-events__date"><span class="m"><?php echo esc_html( $feat[1] ); ?></span><span class="d"><?php echo esc_html( $feat[2] ); ?></span></span>
					<span class="prl-events__today">Today</span>
					<span class="prl-events__fbody">
						<span class="prl-chip prl-chip--sm"><?php echo esc_html( $feat[6] ); ?></span>
						<span class="prl-events__ftitle"><?php echo esc_html( $feat[3] ); ?></span>
						<span class="prl-events__fmeta"><?php echo esc_html( $feat[4] ); ?> &nbsp; <?php echo esc_html( $feat[5] ); ?></span>
						<span class="prl-events__fcta">Event details &rarr;</span>
					</span>
				</a>

				<div class="prl-events__list">
					<?php foreach ( $rest as $e ) : ?>
						<a class="prl-events__row" href="/events/" data-prl-ev-aud="<?php echo esc_attr( $e[6] ); ?>" data-prl-ev-today="<?php echo $e[0] ? '1' : '0'; ?>">
							<span class="prl-events__rdate"><span class="m"><?php echo esc_html( $e[1] ); ?></span><span class="d"><?php echo esc_html( $e[2] ); ?></span></span>
							<span class="prl-events__rbody">
								<span class="prl-events__rtitle"><?php echo esc_html( $e[3] ); ?></span>
								<span class="prl-events__rmeta"><?php echo esc_html( $e[4] ); ?> &bull; <?php echo esc_html( $e[5] ); ?></span>
								<span class="prl-chip prl-chip--sm"><?php echo esc_html( $e[6] ); ?></span>
							</span>
							<span class="prl-events__go" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><path d="M5 12h14m-6-6 6 6-6 6"></path></svg></span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="prl-events__foot">
				<span>Events are run by each member library. Select your library to see its full calendar.</span>
				<a class="prl-btn prl-btn--primary" href="/events/">View All Events &rarr;</a>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'prl_events', 'prl_section_events' );

/* =========================================================
 * 8 - Stream & Download
 * ========================================================= */
function prl_section_stream() {
	$items = array(
		array( 'eBooks', 'Overdrive Libby', '/elibrary/overdrive-libby/', 'M4 5.5C4 4.7 4.7 4 5.5 4H11v16H5.5A1.5 1.5 0 0 1 4 18.5zM20 5.5c0-.8-.7-1.5-1.5-1.5H13v16h5.5c.8 0 1.5-.7 1.5-1.5z' ),
		array( 'Audiobooks', 'cloudLibrary', '/elibrary/cloudlibrary/', 'M4 15v-3a8 8 0 0 1 16 0v3M4 15a2 2 0 0 1 2-2h1v7H6a2 2 0 0 1-2-2zM20 15a2 2 0 0 0-2-2h-1v7h1a2 2 0 0 0 2-2z' ),
		array( 'Magazines & News', 'PressReader', '/elibrary/pressreader/', 'M5 4h11v16H6a2 2 0 0 1-2-2V5a1 1 0 0 1 1-1zM16 8h3a1 1 0 0 1 1 1v9a2 2 0 0 1-2 2M8 8h5M8 12h5M8 16h3' ),
		array( 'Online Courses', 'Universal Class', '/elibrary/universalclass/', 'M2 9l10-5 10 5-10 5zM6 11v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5' ),
		array( 'Languages', 'Transparent Language', '/elibrary/transparent-language-online/', 'M4 6h10M9 4v2c0 4-2 7-5 8M7 11c1.5 2.5 3.5 4 6 5M13 20l4-9 4 9M14.5 17h5' ),
	);
	ob_start();
	?>
	<section class="prl-stream">
		<div class="prl-shell">
			<h2 class="prl-stream__title">Stream &amp; Download</h2>
			<p class="prl-stream__sub">Free with your library card, 24/7</p>
			<div class="prl-stream__grid">
				<?php foreach ( $items as $it ) : ?>
					<a class="prl-stream__item" href="<?php echo esc_url( $it[2] ); ?>">
						<span class="prl-stream__icon"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="<?php echo esc_attr( $it[3] ); ?>"></path></svg></span>
						<span class="prl-stream__label"><?php echo esc_html( $it[0] ); ?></span>
						<span class="prl-stream__via"><?php echo esc_html( $it[1] ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'prl_stream', 'prl_section_stream' );

/* =========================================================
 * 9 - New & Notable. Tabbed shelves, 4.5s.
 * ========================================================= */
function prl_section_books() {
	$shelves = array(
		'Featured Titles' => array(
			array( 'Wintering', 'May, Katherine', 'prl-cover-wintering' ),
			array( "Escape from Mr. Lemoncello's library", 'Grabenstein, Chris', 'prl-cover-lemoncello2' ),
			array( 'Welcome to the Hyunam-dong Bookshop', 'Bo-reum, Hwang', 'prl-cover-hyunam' ),
			array( 'In winter I get up at night', 'Urquhart, Jane', 'prl-cover-inwinter' ),
		),
		'eBook and eAudio' => array(
			array( 'The midnight library', 'Haig, Matt', 'prl-cover-midnight' ),
			array( 'Project Hail Mary', 'Weir, Andy', 'prl-cover-hailmary' ),
			array( 'Lessons in chemistry', 'Garmus, Bonnie', 'prl-cover-chemistry' ),
			array( 'Demon Copperhead', 'Kingsolver, Barbara', 'prl-cover-demon' ),
		),
		'New Books' => array(
			array( 'Remarkably bright creatures', 'Van Pelt, Shelby', 'prl-cover-creatures' ),
			array( 'Demon Copperhead', 'Kingsolver, Barbara', 'prl-cover-demon' ),
			array( 'Wintering', 'May, Katherine', 'prl-cover-wintering' ),
			array( 'The midnight library', 'Haig, Matt', 'prl-cover-midnight' ),
		),
	);
	ob_start();
	?>
	<section class="prl-books" id="newbooks" data-prl-panels>
		<div class="prl-shell">
			<div class="prl-books__head">
				<div class="prl-books__heading">
					<h2>New &amp; Notable</h2>
					<span class="prl-rule-sm" aria-hidden="true"></span>
				</div>
				<div class="prl-books__tabs">
					<?php $i = 0; foreach ( $shelves as $label => $items ) : ?>
						<button type="button" data-prl-tab="shelf<?php echo (int) $i; ?>" aria-selected="<?php echo $i ? 'false' : 'true'; ?>"><?php echo esc_html( $label ); ?></button>
					<?php $i++; endforeach; ?>
				</div>
			</div>
			<?php $i = 0; foreach ( $shelves as $label => $items ) : ?>
				<div class="prl-books__shelf" data-prl-panel="shelf<?php echo (int) $i; ?>"<?php echo $i ? ' hidden' : ''; ?>>
					<?php foreach ( $items as $b ) : $c = prl_img( $b[2], 'medium' ); ?>
						<a class="prl-book" href="https://search.prl.ab.ca/polaris/">
							<span class="prl-book__cover"<?php echo $c ? ' style="background-image:url(' . esc_url( $c ) . ')"' : ''; ?>></span>
							<span class="prl-book__meta">
								<span class="prl-book__author"><?php echo esc_html( $b[1] ); ?> &middot; <?php echo esc_html( $label ); ?></span>
								<span class="prl-book__title"><?php echo esc_html( $b[0] ); ?></span>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php $i++; endforeach; ?>
			<div class="prl-books__foot">
				<a class="prl-btn prl-btn--ghost" href="https://search.prl.ab.ca/polaris/">Browse the catalogue</a>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'prl_books', 'prl_section_books' );

/* =========================================================
 * 10 - Testimonials. 3 quotes, 8s.
 * ========================================================= */
function prl_section_testimonials() {
	$people = array(
		array( 'Sarah Thompson', 'Ponoka Jubilee Library', 'The library has been part of our week for years. Story time gave my kids a love of books, and now they pick out their own every Saturday.', 'prl-testimonial-1' ),
		array( 'Daniel Morin', 'Camrose Public Library', 'I took two courses through Universal Class from home this winter. It cost me nothing but my library card.', 'prl-testimonial-2' ),
		array( 'Grace Okafor', 'Sundre Municipal Library', 'Being able to borrow from any library in Alberta and return it here makes a small-town library feel enormous.', 'prl-testimonial-3' ),
	);
	$bg = prl_img( 'prl-testimonial-bg', 'full' );
	ob_start();
	?>
	<section class="prl-quotes" data-prl-carousel data-prl-interval="8000"<?php echo $bg ? ' style="background-image:url(' . esc_url( $bg ) . ')"' : ''; ?>>
		<span class="prl-quotes__scrim" aria-hidden="true"></span>
		<div class="prl-shell prl-quotes__inner">
			<span class="prl-quotes__eyebrow">Library Stories</span>
			<span class="prl-quotes__mark" aria-hidden="true">&ldquo;</span>
			<?php foreach ( $people as $n => $p ) : ?>
				<div class="prl-quotes__slide" data-prl-slide<?php echo $n ? ' hidden' : ''; ?>>
					<p class="prl-quotes__text"><?php echo esc_html( $p[2] ); ?></p>
					<span class="prl-quotes__name"><?php echo esc_html( $p[0] ); ?></span>
					<span class="prl-quotes__role"><?php echo esc_html( $p[1] ); ?></span>
				</div>
			<?php endforeach; ?>
			<div class="prl-quotes__faces">
				<?php foreach ( $people as $n => $p ) : $f = prl_img( $p[3], 'thumbnail' ); ?>
					<button type="button" data-prl-dot aria-label="<?php echo esc_attr( $p[0] ); ?>"<?php echo $n ? '' : ' aria-current="true"'; ?><?php echo $f ? ' style="background-image:url(' . esc_url( $f ) . ')"' : ''; ?>></button>
				<?php endforeach; ?>
			</div>
			<button type="button" class="prl-quotes__arrow prl-quotes__arrow--prev" data-prl-prev aria-label="Previous"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><path d="M19 12H5m6-6-6 6 6 6"></path></svg></button>
			<button type="button" class="prl-quotes__arrow prl-quotes__arrow--next" data-prl-next aria-label="Next"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><path d="M5 12h14m-6-6 6 6-6 6"></path></svg></button>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'prl_testimonials', 'prl_section_testimonials' );

/* =========================================================
 * 11 - Donate
 * ========================================================= */
function prl_section_donate() {
	$photo = prl_img( 'prl-donate', 'large' );
	ob_start();
	?>
	<section class="prl-donate" id="donate">
		<div class="prl-shell">
			<div class="prl-donate__card">
				<div class="prl-donate__body">
					<span class="prl-donate__tag"><svg width="14" height="14" viewBox="0 0 24 24" fill="#9FC48A" aria-hidden="true"><path d="M12 21s-8-5.2-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 5.8-8 11-8 11z"></path></svg>Support the library</span>
					<h2 class="prl-donate__title">Help your library keep growing with its community</h2>
					<p class="prl-donate__text">Donate today and help ensure that the library continues to evolve to meet the needs of our community.</p>
					<div class="prl-donate__amounts" role="group" aria-label="Donation amount">
						<?php foreach ( array( '$25', '$50', '$100', 'Other' ) as $i => $a ) : ?>
							<button type="button" class="prl-donate__amt<?php echo 1 === $i ? ' is-active' : ''; ?>" data-prl-amt="<?php echo esc_attr( $a ); ?>" aria-pressed="<?php echo 1 === $i ? 'true' : 'false'; ?>"><?php echo esc_html( $a ); ?></button>
						<?php endforeach; ?>
					</div>
					<div class="prl-donate__actions">
						<a class="prl-donate__cta" href="/donate/" data-prl-donate-cta>Donate $50 &rarr;</a>
						<a class="prl-donate__alt" href="/about-us/advocacy/">Other ways to help</a>
					</div>
				</div>
				<div class="prl-donate__photo"<?php echo $photo ? ' style="background-image:url(' . esc_url( $photo ) . ')"' : ''; ?>>
					<span class="prl-donate__scrim" aria-hidden="true"></span>
					<span class="prl-donate__badge">
						<span class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2F5B43" stroke-width="2"><path d="M4 5.5C4 4.7 4.7 4 5.5 4H11v16H5.5A1.5 1.5 0 0 1 4 18.5zM20 5.5c0-.8-.7-1.5-1.5-1.5H13v16h5.5c.8 0 1.5-.7 1.5-1.5z"></path></svg></span>
						<span class="txt"><span class="t">Serving since 1959</span><span class="s">Alberta's first regional library</span></span>
					</span>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'prl_donate', 'prl_section_donate' );
