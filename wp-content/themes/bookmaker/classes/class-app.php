<?php 

class App {
    
    public function __construct() {
        add_action( 'init', array($this, 'create_taxonomy_langs') );
        add_action( 'init', array($this, 'create_taxonomy_device') );
        add_action( 'init', array($this, 'create_taxonomy_payment') );
        add_action( 'init', array($this, 'register_post_type_bookmaker') );
        add_action( 'init', array($this, 'register_post_type_currency') );
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts') );
        add_theme_support( 'post-thumbnails' );


        $maximum_withdrawal_limit = new NumericFieldWithCurrencyDropdownList('currency', ['input.name', 'select.post_id'], 'bookmaker', [
            	'day' => 'День',
            	'week' => 'Неделя',
            	'month' => 'Месяц'
            ], 'Макс. лимит на вывод средств'
        );
        $minimum_withdrawal_amount = new NumericFieldWithCurrencyDropdownList('currency', ['input.name', 'select.post_id'], 'bookmaker', null, 'Минимальная сумма вывода', 'another');

        $maximum_withdrawal_limit->render();
        $minimum_withdrawal_amount->render();
    }

    public function admin_enqueue_scripts() {
        // wp_enqueue_script('admin-script', get_template_directory_uri() . '/admin/js/script.js', array(), false, true);
        wp_enqueue_style('admin-styles', get_template_directory_uri() . '/admin/css/style.css', array(), null);
    }
    public function create_taxonomy_langs() {
    	register_taxonomy( 'langs', [ 'bookmaker' ], [
    		'labels'                => [
    			'name'              => 'Языки',
    			'singular_name'     => 'Язык',
    			'search_items'      => 'Искать языки',
    			'all_items'         => 'Все языки',
    			'view_item '        => 'Смотреть язык',
    			'edit_item'         => 'Редактировать язык',
    			'update_item'       => 'Обновить язык',
    			'add_new_item'      => 'Добавить новый язык',
    			'new_item_name'     => 'Новое название языка',
    			'menu_name'         => 'Языки',
    			'back_to_items'     => '← Назад к языку',
    		],
    		'description'           => 'Языки сайтов букмекеров', // описание таксономии
    		'meta_box_cb'           => 'post_categories_meta_box', // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
    		'show_in_rest'          => true, // добавить в REST API
    	] );
    }
    public function create_taxonomy_device(){
    	register_taxonomy( 'device', [ 'bookmaker' ], [
    		'labels'                => [
    			'name'              => 'Устройства',
    			'singular_name'     => 'Устройство',
    			'search_items'      => 'Искать устройства',
    			'all_items'         => 'Все устройства',
    			'view_item '        => 'Смотреть устройство',
    			'edit_item'         => 'Редактировать устройство',
    			'update_item'       => 'Обновить устройство',
    			'add_new_item'      => 'Добавить новое устройство',
    			'new_item_name'     => 'Новое название устройства',
    			'menu_name'         => 'Устройства',
    			'back_to_items'     => '← Назад к устройствам',
    		],
    		'description'           => 'Поддерживаемые устройства букмекеров', // описание таксономии
    		'meta_box_cb'           => 'post_categories_meta_box', // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
    		'show_in_rest'          => true, // добавить в REST API
    	] );
    }
    public function create_taxonomy_payment(){
    	register_taxonomy( 'payment', [ 'bookmaker' ], [
    		'labels'                => [
    			'name'              => 'Платежные системы',
    			'singular_name'     => 'Платежная система',
    			'search_items'      => 'Искать платежные системы',
    			'all_items'         => 'Все платежные системы',
    			'view_item '        => 'Смотреть платежную систему',
    			'edit_item'         => 'Редактировать платежную систему',
    			'update_item'       => 'Обновить платежную систему',
    			'add_new_item'      => 'Добавить новую платежную систему',
    			'new_item_name'     => 'Новое название платежной системы',
    			'menu_name'         => 'Платежные системы',
    			'back_to_items'     => '← Назад к платежным системам',
    		],
    		'description'           => 'Поддерживаемые платежные системы букмекеров', // описание таксономии
    		'meta_box_cb'           => 'post_tags_meta_box', // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
    		'show_in_rest'          => true, // добавить в REST API
    	] );
    }

    public function register_post_type_bookmaker(){
    	register_post_type( 'bookmaker', [
    		'labels' => [
    			'name'               => 'Букмекеры', // основное название для типа записи
    			'singular_name'      => 'Букмекер', // название для одной записи этого типа
    			'add_new'            => 'Добавить букмекера', // для добавления новой записи
    			'add_new_item'       => 'Добавление букмекера', // заголовка у вновь создаваемой записи в админ-панели.
    			'edit_item'          => 'Редактирование букмекера', // для редактирования типа записи
    			'new_item'           => 'Новый букмекер', // текст новой записи
    			'view_item'          => 'Смотреть букмекера', // для просмотра записи этого типа.
    			'search_items'       => 'Искать букмекера', // для поиска по этим типам записи
    			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
    			'menu_name'          => 'Букмекеры', // название меню
    		],
    		'description'         => 'Букмекеры на сайте',
    		'public'              => true,
    		'show_in_rest'        => true, // добавить в REST API. C WP 4.7
    		// 'menu_position'       => null,
    		'menu_icon'           => 'dashicons-businessperson',
    		'supports'            => [ 'title' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
    	] );
    }

    public function register_post_type_currency(){
    	register_post_type( 'currency', [
    		'labels' => [
    			'name'               => 'Валюта', // основное название для типа записи
    			'singular_name'      => 'Валюта', // название для одной записи этого типа
    			'add_new'            => 'Добавить валюту', // для добавления новой записи
    			'add_new_item'       => 'Добавление валюты', // заголовка у вновь создаваемой записи в админ-панели.
    			'edit_item'          => 'Редактирование валюты', // для редактирования типа записи
    			'new_item'           => 'Новая валюта', // текст новой записи
    			'view_item'          => 'Смотреть валюту', // для просмотра записи этого типа.
    			'search_items'       => 'Искать валюту', // для поиска по этим типам записи
    			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
    			'menu_name'          => 'Валюты', // название меню
    		],
    		'description'         => 'Валюты на сайте для букмекеров',
    		'public'              => false,
    		'show_in_rest'        => true, // добавить в REST API. C WP 4.7
            'show_ui'             => true,
    		// 'menu_position'       => null,
    		'menu_icon'           => 'dashicons-admin-site-alt3',
    		'supports'            => [ 'title', 'excerpt', 'thumbnail' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
    	] );
    }
}