<?php 

/**
 * Класс для добавления числовых полей с выпадающими списками валют 
 * для определенных разработчиком периодов.
 */
class NumericFieldWithCurrencyDropdownList {
	/**
	 * Тип поста, к которому привязываем вывод в select
	 * 
	 * @var array
	 */
	public $included_post_type;

	/**
	 * Поля в вдминке
	 *
	 * $fields = ['input.name', 'select.post_id']
	 * @var array
	 */
	public $fields;

	/**
	 * Название метабокса в админке
	 *
	 * @var string
	 */
	public $metabox_name;

	/**
	 * meta_key в wp_postmeta
	 * 
	 * Начало названия столбца meta_key в таблице wp_postmeta в БД. 
	 * Должен совпадать с названием файла отображения (./templates/{filename})
	 *
	 * @var string
	 */
	public $pre_meta_key;

	/**
	 * Путь до директории отображения.
	 *
	 * @var string
	 */
	public $templates_dir = __DIR__ . '/templates/';

	/**
	 * Тип поста, к которому привязываем метабокс
	 *
	 * @var string
	 */
	public $post_type;

	/**
	 * Периоды 
	 * 
	 * День, неделя, месяц и т.д.). Названия любые, неограничено.
	 * Ключи добавляются в meta_key в таблице wp_postmeta в БД.
	 * $post_meta_keys = [
	 *	'day' => 'День',
	 *	'week' => 'Неделя',
	 *	'month' => 'Месяц'
	 * ];
	 *
	 * @var array
	 */
	public $post_meta_keys;

	/**
	 * Тип подключаемого файла отображения
	 *
	 * @var string
	 */
	public $template_type;

	public function __construct(
		$included_post_type, 
		$fields, 
		$post_type,
		$post_meta_keys,
		$metabox_name = '',
		$pre_meta_key = 'withdrawal',
		$templates_dir = __DIR__ . '/templates/',
		$template_type = '.php'
	) {
		$this->included_post_type = $included_post_type;
		$this->fields = $fields;
		$this->post_type = $post_type;
		$this->post_meta_keys = $post_meta_keys;
		$this->metabox_name = $metabox_name;
		$this->pre_meta_key = $pre_meta_key;
		$this->templates_dir = $templates_dir;
		$this->template_type = $template_type;

		if (empty($this->fields)) {
			throw new \Exception("\$fields is empty. Add a value or remove an instance of the NumericFieldWithCurrencyDropdownList class", 1);
		}
		if (empty($this->post_type)) {
			throw new \Exception("\$post_type is empty. Add a value or remove an instance of the NumericFieldWithCurrencyDropdownList class", 1);
		}
		if (empty($this->post_meta_keys) && $this->post_meta_keys !== null) {
			throw new \Exception("\$post_meta_keys is empty. Add a value or remove an instance of the NumericFieldWithCurrencyDropdownList class. Pass NULL if not needed", 1);
		}

	}

	public function render() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post_' . $this->post_type, array( $this, 'save_metabox' ) );
	}

	// Добавляет матабоксы
	public function add_metabox() {
		add_meta_box( 'box_info_' . $this->pre_meta_key, $this->metabox_name, array( $this, 'render_metabox' ), $this->post_type, 'advanced', 'high' );
	}

	// Отображает метабокс на странице редактирования поста
	public function render_metabox() {
		global $post;
		global $wpdb;

		// Template included vars 
		$post_metas = [];
		$pre_meta_key = $this->pre_meta_key;
		$post_meta_keys = $this->post_meta_keys;
		$fields = $this->fields;
		$str_meta_keys = $this->getStringMetaKeys();
		$currencies = get_posts([
		    'post_type' => $this->included_post_type,
		    'posts_per_page' => -1,
		]);

		$query = "SELECT meta_id, meta_key, meta_value FROM wp_postmeta 
			WHERE post_id = {$post->ID} AND meta_key IN(" . $this->getStringMetaKeys() . ")";

		foreach ($wpdb->get_results($query, ARRAY_A) as $post_key => $post_meta) {
			$post_metas[$post_meta['meta_key']] = $post_meta;
		}

		include $this->templates_dir . 'withdrawal' . ($this->post_meta_keys !== null ? '-multi' : false) . $this->template_type;
	}

	// Очищает и сохраняет значения полей
	public function save_metabox( $post_id ) {
		$pre_meta_key = $this->pre_meta_key;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		if ( isset( $_POST[$pre_meta_key] ) && is_array( $_POST[$pre_meta_key] ) ) {

			// очистка
			if ($this->post_meta_keys !== null) {
				$fields = array_map(function ($a) {
					// $a = array_filter( $a );
					return array_map( 'sanitize_text_field', $a );
				}, $_POST[$pre_meta_key]);
			} else {
				$fields = array_map( 'sanitize_text_field', $_POST[$pre_meta_key] );
			}


			// мужики, у меня нет времени через рекурсию описывать на нижнем условии, 
			// знаю что много кода лишнего от этого.. строго не судите
			if ($this->post_meta_keys !== null) {
				foreach ($fields as $field_key => $metas) {

					foreach ($metas as $meta_key => $meta_value) {

						$meta_field = "$pre_meta_key.$field_key.$meta_key";
						if (empty($meta_value)) {
							delete_post_meta( $post_id, $meta_field );
							continue;
						}

						update_post_meta( $post_id, $meta_field, $meta_value );
					}
				}
			} else {
				foreach ($fields as $field_key => $metas) {

					$meta_field = "$pre_meta_key.$field_key";
					if (empty($metas)) {
						delete_post_meta( $post_id, $meta_field );
						continue;
					}

					update_post_meta( $post_id, $meta_field, $metas );

				}
			}

		}
	}

	// помощник
	public function getStringMetaKeys () {
		global $wpdb;

	    $fin = [];
		$pre_meta_key = $this->pre_meta_key;

		if ($this->post_meta_keys !== NULL) {
			foreach ($this->post_meta_keys as $meta_key => $meta_value) {
				foreach ($this->fields as $field) {
					$fin[] = "$meta_key.$field";
				}
			}
		} else {
			foreach ($this->fields as $field) {
				$fin[] = "$field";
			}
		}

		return implode(',', array_map(function($item) use ($pre_meta_key, $wpdb){
			return $wpdb->prepare('%s', "$pre_meta_key.$item");
		}, $fin));

	}
}

