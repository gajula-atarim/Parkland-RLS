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


/* =========================================================
 * 7b - Events feed from The Events Calendar (added 2026-11-25).
 *
 * [prl_event_feed] renders the audience chips and the feature card + list
 * from real events, so staff manage everything under Events > Add New:
 *   - Title, date and time        -> card title, date chip, time
 *   - Venue                       -> library name
 *   - Event Category              -> audience (chips + small label)
 *   - Featured image              -> feature card photo
 *   - "Feature event" checkbox    -> pins the event as the big card
 * The section heading, toggle and "View All Events" button stay Elementor
 * widgets. Markup and classes match PRL Home v3, so prl-home2.css styles it
 * and prl-home.js drives the chips and the All / Today toggle.
 *
 * Attributes: rows="4" (rows shown at once), limit="20" (rows loaded so the
 * chips still have something to filter), category="" (slug, optional).
 * ========================================================= */

function prl_event_feed_audiences() {
	$terms = get_terms( array(
		'taxonomy'   => 'tribe_events_cat',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	) );
	return ( is_array( $terms ) ) ? $terms : array();
}

function prl_event_feed_item( $event, $today ) {
	$id        = is_object( $event ) ? $event->ID : (int) $event;
	$start     = tribe_get_start_date( $id, false, 'Y-m-d' );
	$end       = tribe_get_end_date( $id, false, 'Y-m-d' );
	$all_day   = function_exists( 'tribe_event_is_all_day' ) && tribe_event_is_all_day( $id );
	$terms     = get_the_terms( $id, 'tribe_events_cat' );
	$aud_names = array();
	$aud_ids   = array();
	if ( is_array( $terms ) ) {
		foreach ( $terms as $t ) {
			$aud_names[] = $t->name;
			$aud_ids[]   = (int) $t->term_id;
		}
	}
	return array(
		'id'       => $id,
		'title'    => get_the_title( $id ),
		'url'      => get_permalink( $id ),
		'month'    => strtoupper( tribe_get_start_date( $id, false, 'M' ) ),
		'day'      => tribe_get_start_date( $id, false, 'd' ),
		'time'     => $all_day ? __( 'All day', 'parkland' ) : tribe_get_start_date( $id, false, get_option( 'time_format', 'g:i a' ) ),
		'venue'    => function_exists( 'tribe_get_venue' ) ? wp_strip_all_tags( (string) tribe_get_venue( $id ) ) : '',
		'aud'      => $aud_names,
		'aud_ids'  => $aud_ids,
		'is_today' => ( $start <= $today && $end >= $today ),
		'img'      => get_the_post_thumbnail_url( $id, 'large' ),
		'featured' => (bool) get_post_meta( $id, '_tribe_featured', true ),
	);
}

function prl_event_feed( $atts = array() ) {
	$atts = shortcode_atts( array(
		'rows'     => 4,
		'limit'    => 20,
		'category' => '',
	), $atts, 'prl_event_feed' );

	if ( ! function_exists( 'tribe_get_events' ) ) {
		return current_user_can( 'edit_posts' )
			? '<p class="prl-events__notice">' . esc_html__( 'The Events Calendar plugin is not active, so no events can be shown.', 'parkland' ) . '</p>'
			: '';
	}

	try {
		$today = current_time( 'Y-m-d' );
		$args  = array(
			'start_date'     => $today . ' 00:00:00',
			'eventDisplay'   => 'list',
			'posts_per_page' => max( 1, (int) $atts['limit'] ) + 1,
		);
		if ( '' !== $atts['category'] ) {
			$args['tax_query'] = array( array( 'taxonomy' => 'tribe_events_cat', 'field' => 'slug', 'terms' => $atts['category'] ) );
		}
		$events = tribe_get_events( $args );
		$items  = array();
		foreach ( (array) $events as $e ) {
			$items[] = prl_event_feed_item( $e, $today );
		}

		/* Feature: the first upcoming event ticked "Feature event", else the first one. */
		$feat_key = 0;
		foreach ( $items as $k => $it ) {
			if ( $it['featured'] ) { $feat_key = $k; break; }
		}
		$feat = $items ? $items[ $feat_key ] : null;
		if ( $items ) { unset( $items[ $feat_key ] ); }
		$rows = array_slice( array_values( $items ), 0, (int) $atts['limit'] );

		$auds = prl_event_feed_audiences();
		$index = array(); // term_id => chip index, matching prl-home.js (chip 0 is "All").
		foreach ( $auds as $i => $t ) { $index[ (int) $t->term_id ] = $i + 1; }

		$fallback_img = function_exists( 'prl_img' ) ? prl_img( 'prl-event-1', 'large' ) : '';
		$all_url      = function_exists( 'tribe_get_events_link' ) ? tribe_get_events_link() : home_url( '/events/' );

		ob_start();
		?>
		<div class="prl-event-feed">
			<div class="prl-events__chips" role="group" aria-label="<?php esc_attr_e( 'Filter by audience', 'parkland' ); ?>">
				<button type="button" class="prl-chip is-active" aria-pressed="true"><?php esc_html_e( 'All', 'parkland' ); ?></button>
				<?php foreach ( $auds as $t ) : ?>
					<button type="button" class="prl-chip" aria-pressed="false"><?php echo esc_html( $t->name ); ?></button>
				<?php endforeach; ?>
			</div>

			<?php if ( ! $feat ) : ?>
				<p class="prl-events__empty-all"><?php esc_html_e( 'No upcoming events right now. Please check back soon.', 'parkland' ); ?></p>
			<?php else :
				$img = $feat['img'] ? $feat['img'] : $fallback_img; ?>
			<div class="prl-events__grid">
				<a class="prl-events__feature" href="<?php echo esc_url( $feat['url'] ); ?>">
					<span class="prl-events__fimg" aria-hidden="true"<?php echo $img ? ' style="background-image:url(' . esc_url( $img ) . ')"' : ''; ?>></span>
					<span class="prl-events__date"><span class="m"><?php echo esc_html( $feat['month'] ); ?></span><span class="d"><?php echo esc_html( $feat['day'] ); ?></span></span>
					<?php if ( $feat['is_today'] ) : ?><span class="prl-events__today"><?php esc_html_e( 'Today', 'parkland' ); ?></span><?php endif; ?>
					<span class="prl-events__fbody">
						<?php if ( $feat['aud'] ) : ?><span class="prl-chip prl-chip--sm"><?php echo esc_html( $feat['aud'][0] ); ?></span><?php endif; ?>
						<span class="prl-events__ftitle"><?php echo esc_html( $feat['title'] ); ?></span>
						<span class="prl-events__fmeta"><?php echo esc_html( trim( $feat['time'] . '  ' . $feat['venue'] ) ); ?></span>
						<span class="prl-events__fcta"><?php esc_html_e( 'Event details', 'parkland' ); ?> &rarr;</span>
					</span>
				</a>

				<div class="prl-events__list" data-prl-rows="<?php echo (int) $atts['rows']; ?>">
					<?php foreach ( $rows as $r ) :
						$cls = array( 'prl-events__row', 'prl-ev-today-' . ( $r['is_today'] ? '1' : '0' ) );
						foreach ( $r['aud_ids'] as $tid ) {
							if ( isset( $index[ $tid ] ) ) { $cls[] = 'prl-ev-aud-' . $index[ $tid ]; }
						} ?>
						<a class="<?php echo esc_attr( implode( ' ', $cls ) ); ?>" href="<?php echo esc_url( $r['url'] ); ?>">
							<span class="prl-events__rdate"><span class="m"><?php echo esc_html( $r['month'] ); ?></span><span class="d"><?php echo esc_html( $r['day'] ); ?></span></span>
							<span class="prl-events__rbody">
								<span class="prl-events__rtitle"><?php echo esc_html( $r['title'] ); ?></span>
								<span class="prl-events__rmeta"><?php echo esc_html( $r['time'] ); ?><?php echo $r['venue'] ? ' &bull; ' . esc_html( $r['venue'] ) : ''; ?></span>
								<?php if ( $r['aud'] ) : ?><span class="prl-chip prl-chip--sm"><?php echo esc_html( implode( ', ', $r['aud'] ) ); ?></span><?php endif; ?>
							</span>
							<span class="prl-events__go" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"><path d="M5 12h14m-6-6 6 6-6 6"></path></svg></span>
						</a>
					<?php endforeach; ?>
					<p class="prl-events__empty is-hidden"><?php esc_html_e( 'No upcoming events match this filter.', 'parkland' ); ?> <a href="<?php echo esc_url( $all_url ); ?>"><?php esc_html_e( 'See the full calendar', 'parkland' ); ?> &rarr;</a></p>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	} catch ( \Throwable $e ) {
		return current_user_can( 'edit_posts' )
			? '<p class="prl-events__notice">' . esc_html__( 'Events could not be loaded.', 'parkland' ) . '</p>'
			: '';
	}
}
add_shortcode( 'prl_event_feed', 'prl_event_feed' );

/**
 * One-time setup on the main site: the audience categories used by the chips,
 * plus sample events so the section is populated. Runs once (option flag) and
 * only when The Events Calendar is active. Staff can edit or delete the samples.
 */
function prl_event_feed_setup() {
	if ( get_option( 'prl_event_feed_setup' ) ) { return; }
	if ( ! function_exists( 'tribe_create_event' ) || ! taxonomy_exists( 'tribe_events_cat' ) ) { return; }
	if ( is_multisite() && ! is_main_site() ) { return; }
	update_option( 'prl_event_feed_setup', 'running', false );

	try {
		$terms = array();
		foreach ( array( 'Adult', 'All Ages', 'Babies & Toddlers', 'Families', 'Kids (2 - 6)', 'Kids (6 - 12)', 'Preteens (9 - 12 yrs)', 'Seniors', 'Teens (13 - 17 yrs)' ) as $name ) {
			$found = get_term_by( 'name', $name, 'tribe_events_cat' );
			if ( $found ) {
				$terms[ $name ] = (int) $found->term_id;
			} else {
				$made = wp_insert_term( $name, 'tribe_events_cat' );
				if ( ! is_wp_error( $made ) ) { $terms[ $name ] = (int) $made['term_id']; }
			}
		}

		$have = get_posts( array( 'post_type' => 'tribe_events', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) );
		if ( empty( $have ) ) {
			$venues = array();
			$samples = array(
				// title, days from today, start H:i, end H:i, library, audience, featured
				array( 'Baby & Me Rhyme Time', 0, '10:30', '11:15', 'Ponoka Jubilee Library', 'Babies & Toddlers', true ),
				array( 'Tech Help Drop-in', 0, '14:00', '16:00', 'Camrose Public Library', 'Seniors', false ),
				array( 'Lego Club', 0, '15:30', '16:30', 'Blackfalds Public Library', 'Kids (6 - 12)', false ),
				array( 'Story Time', 2, '10:00', '10:45', 'Innisfail Public Library', 'Kids (2 - 6)', false ),
				array( 'Teen Gaming Night', 4, '18:30', '20:30', 'Sundre Municipal Library', 'Teens (13 - 17 yrs)', false ),
				array( 'Winter Reads Book Club', 5, '19:00', '20:30', 'Alix Public Library', 'Adult', false ),
				array( 'Preteen Writing Workshop', 7, '16:00', '17:30', 'Lacombe - Mary C. Moore Public Library', 'Preteens (9 - 12 yrs)', false ),
				array( 'Family Craft Afternoon', 9, '13:00', '15:00', 'Rocky Mountain House Public Library', 'Families', false ),
				array( 'Holiday Movie Matinee', 11, '14:00', '16:00', 'Castor Municipal Library', 'All Ages', false ),
			);
			$img = get_page_by_path( 'prl-event-1', OBJECT, 'attachment' );
			$tz  = wp_timezone();
			foreach ( $samples as $s ) {
				list( $title, $days, $from, $to, $lib, $aud, $featured ) = $s;
				if ( ! isset( $venues[ $lib ] ) && function_exists( 'tribe_create_venue' ) ) {
					$venues[ $lib ] = (int) tribe_create_venue( array( 'Venue' => $lib, 'Province' => 'AB', 'Country' => 'Canada', 'post_status' => 'publish' ) );
				}
				$day   = ( new DateTimeImmutable( 'now', $tz ) )->modify( '+' . (int) $days . ' days' )->format( 'Y-m-d' );
				$start = DateTimeImmutable::createFromFormat( 'Y-m-d H:i', $day . ' ' . $from, $tz );
				$end   = DateTimeImmutable::createFromFormat( 'Y-m-d H:i', $day . ' ' . $to, $tz );
				$args  = array(
					'post_title'         => $title,
					'post_content'       => 'Sample event added during setup. Edit or delete it under Events in the dashboard.',
					'post_status'        => 'publish',
					'EventStartDate'     => $day,
					'EventEndDate'       => $day,
					'EventStartHour'     => $start->format( 'h' ),
					'EventStartMinute'   => $start->format( 'i' ),
					'EventStartMeridian' => $start->format( 'a' ),
					'EventEndHour'       => $end->format( 'h' ),
					'EventEndMinute'     => $end->format( 'i' ),
					'EventEndMeridian'   => $end->format( 'a' ),
					'EventTimezone'      => wp_timezone_string(),
				);
				if ( ! empty( $venues[ $lib ] ) ) { $args['Venue'] = array( 'VenueID' => $venues[ $lib ] ); }
				$id = tribe_create_event( $args );
				if ( ! $id || is_wp_error( $id ) ) { continue; }
				if ( isset( $terms[ $aud ] ) ) { wp_set_object_terms( $id, array( $terms[ $aud ] ), 'tribe_events_cat' ); }
				if ( $featured ) {
					// Same meta key the "Feature event" checkbox writes.
					update_post_meta( $id, '_tribe_featured', 1 );
					if ( $img ) { set_post_thumbnail( $id, $img->ID ); }
				}
			}
		}
		update_option( 'prl_event_feed_setup', 'done', false );
	} catch ( \Throwable $e ) {
		update_option( 'prl_event_feed_setup', 'failed: ' . $e->getMessage(), false );
	}
}
add_action( 'init', 'prl_event_feed_setup', 99 );
