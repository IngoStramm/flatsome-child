<?php
/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class Cf_Admin {

	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'cf_options';

	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'cf_option_metabox';

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Holds an instance of the object
	 *
	 * @var Cf_Admin
	 **/
	private static $instance = null;

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	private function __construct() {
		// Set our title
		$this->title = __( 'Parcelamento', 'cf' );
	}

	/**
	 * Returns the running object
	 *
	 * @return Cf_Admin
	 **/
	public static function get_instance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new Cf_Admin();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
	}


	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page( $this->title, $this->title, 'delete_users', $this->key, array( $this, 'admin_page_display' ) );

		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {

		$utils = new Utils;
		// $utils->debug( $this->metabox_id );

		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );
		// Set our CMB2 fields


		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		// Set our CMB2 fields

		$gateways = $utils->get_all_gateways();
		$count = 0;

		// $gateways = [];

		foreach ($gateways as $gateway) :

			// $utils->debug( $gateway );

			if( $count > 0 ) :

				$cmb->add_field( array(
					'name'    => '<hr />',
					'id'    => 'gateway_separator_' . $count,
					'type'      => 'title',
					'attributes' => array(
							'class' => 'cbm2-separator'
						)
				) );

			else:

				$cmb->add_field( array(
					'name'    => __( '<h3 style="color: #f00;">ATENÇÃO!</h3><p style="color: #f00; font-weight: bold;">Estas configurações de parcelamento servem apenas para fins de informação, não estão vinculadas aos métodos de pagamento da loja. É necessário definir o parcelamento para cada método de pagamento usado, no próprio site do respectivo método de pagamento.</p><p style="color: #f00; font-weight: bold;"><strong>Obs:</strong> É importante que as informações dos parcelamentos cadastrados nesta área, sejam consistentes com o parcelamento oferecido pelos métodos de pagamento.</p>', 'cf' ),
					'id'    => 'gateway_separator_' . $count,
					'type'      => 'title',
					'attributes' => array(
							'class' => 'cbm2-separator'
						)
				) );

			endif;

			$cmb->add_field( array(
				'name'    => $gateway,
				'id'    => 'gateway_title_' . $count,
				'type'      => 'title',
			) );

			$cmb->add_field( array(
				'name' => esc_html__( 'Ativar', 'cf' ),
				'id'   => 'ativo_' . $count,
				'type' => 'checkbox',
			) );

			$cmb->add_field( array(
				'name'       => esc_html__( 'Número de parcelas sem juros', 'cf' ),
				'id'         => 'numero_parc_sem_juros_' . $count,
				'type'       => 'select',
				'options'	 => get_total_parc()
			) );

			$cmb->add_field( array(
				'name' => esc_html__( 'Número de parcelas com juros', 'cmb2' ),
				'desc'    => esc_html__( 'Adicionar porcentagem da parcela com juros. O número da parcela é progressivo, continuando de onde o número de parcelas sem juro parou. Por ex: se o parcelamento for configurado 3 vezes sem juros, o primeiro número de parcelas com juros  será em 4 vezes com juros. O seguinte, será em 5 vezes sem juros e assim por diante.', 'cf' ),
				'id'   => 'numero_parc_com_juros_' . $count,
				'type' => 'text',
				'attributes' => array(
						'type' => 'number',
						'pattern' => '[0-9]+([\.,][0-9]+)?',
						'step' => '0.5'
					),
				'repeatable' => true
			) );

			$count++;

		endforeach;
	}

	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'cmb2' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

}

/**
 * Helper function to get/return the Cf_Admin object
 * @since  0.1.0
 * @return Cf_Admin object
 */
function cf_admin() {
	return Cf_Admin::get_instance();
}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function cf_get_option( $key = '' ) {
	return cmb2_get_option( cf_admin()->key, $key );
}

function get_total_parc() {
	$parc = [];
	$total_parc = 32;
	for ( $i = 1; $i <= $total_parc; $i++ ) :
		$parc[$i] = $i;
	endfor;
	return $parc;
}

// Get it started
cf_admin();

add_action( 'admin_head', 'cmb2_parcelamento_style' );

function cmb2_parcelamento_style() {
	?>
	<style>
		.cbm2-separator {
			display: block;
			margin: 40px 0;
			position: relative;
		}
			.cbm2-separator:before {
				/*content: '<hr />';*/
				display: block;
				margin: 20px 0;
			}
	</style>
	<?php
}