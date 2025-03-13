<?php
/**
 * Plugin Name:       extra_custom_posts
 * Description:       Custom posts build by Jennifer.
 * Version:           1
 * Author:            Jennifer
 * Text Domain:       extra_custom_posts
 *
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// ✅ 1. Registreer Custom Post Types (Boeken & Programmeertalen)
function registreer_boek_cpt()
{
    $labels = array(
        'name'               => 'Boeken',
        'singular_name'      => 'Boek',
        'menu_name'          => 'Boeken',
        'name_admin_bar'     => 'Boek Toevoegen',
        'add_new'            => 'Nieuw Boek',
        'add_new_item'       => 'Nieuw Boek Toevoegen',
        'edit_item'          => 'Boek Bewerken',
        'new_item'           => 'Nieuw Boek',
        'view_item'          => 'Bekijk Boek',
        'search_items'       => 'Zoek Boeken',
        'not_found'          => 'Geen boeken gevonden',
        'not_found_in_trash' => 'Geen boeken in de prullenbak'
    );

    $args = array(
        'label'               => 'boeken',
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'boeken'),
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-book',
        'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_rest'        => true,
    );

    register_post_type('boek', $args);
}
add_action('init', 'registreer_boek_cpt');

function registreer_programeer_taal_cpt()
{
    $labels = array(
        'name' => 'Programeertalen',
        'singular_name' => 'Programeertaal',
        'menu_name' => 'Programeertalen',
        'name_admin_bar' => 'Programeertaal Toevoegen',
        'add_new' => 'Nieuwe Programmeertaal',
        'add_new_item' => 'Nieuwe Programmeertaal Toevoegen',
        'edit_item' => 'Programmeer taal Bewerken',
        'new_item' => 'Nieuwe Programmeertaal',
        'view_item' => 'Bekijk Programmeertaal',
        'search_items' => 'Zoek Programmeertalen',
        'not_found' => 'Geen Programmeertalen gevonden',
        'not_found_in_trash' => 'Geen Programmeertalen in de prullenbak'
    );

    $args = array(
        'label'               => 'programeertalen',
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'programeertalen'),
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-awards',
        'supports'            => array('title', 'editor', 'custom-fields'),
        'show_in_rest'        => true,
    );

    register_post_type('programmeertaal', $args);
}
add_action('init', 'registreer_programeer_taal_cpt');

function registreer_boek_taxonomieen() {
    // Soort Taxonomie
    $soort_labels = array(
        'name'              => 'Soort',
        'singular_name'     => 'Soort',
        'search_items'      => 'Zoek Soort',
        'all_items'         => 'Alle Soort',
        'parent_item'       => 'Bovenliggende Soort',
        'parent_item_colon' => 'Bovenliggende Soort:',
        'edit_item'         => 'Bewerk Soort',
        'update_item'       => 'Update Soort',
        'add_new_item'      => 'Voeg Nieuw Soort Toe',
        'new_item_name'     => 'Nieuwe Soort Naam',
        'menu_name'         => 'Soort',
    );

    $soort_args = array(
        'labels'            => $soort_labels,
        'hierarchical'      => true, // ✅ Werkt als categorieën (hiërarchisch)
        'public'            => true,
        'show_admin_column' => true, // ✅ Zorgt ervoor dat de kolom in het admin-overzicht verschijnt
        'show_in_rest'      => true, // ✅ Nodig voor Gutenberg en de blok-editor
    );

    register_taxonomy('soort', 'boek', $soort_args);

    // Boek Tags Taxonomie
    $tag_labels = array(
        'name'                       => 'Boek Tags',
        'singular_name'              => 'Boek Tag',
        'search_items'               => 'Zoek Boek Tags',
        'popular_items'              => 'Populaire Boek Tags',
        'all_items'                  => 'Alle Boek Tags',
        'edit_item'                  => 'Bewerk Boek Tag',
        'update_item'                => 'Update Boek Tag',
        'add_new_item'               => 'Voeg Nieuwe Boek Tag Toe',
        'new_item_name'              => 'Nieuwe Boek Tag Naam',
        'separate_items_with_commas' => 'Scheid boek tags met komma’s',
        'add_or_remove_items'        => 'Tags toevoegen of verwijderen',
        'choose_from_most_used'      => 'Kies uit de meest gebruikte tags',
        'menu_name'                  => 'Boek Tags',
    );

    $tag_args = array(
        'labels'            => $tag_labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
    );

    register_taxonomy('boek_tags', 'boek', $tag_args);
}

add_action('init', 'registreer_boek_taxonomieen');

// Voeg meta box toe aan boek-editpagina
function boek_koppelingen_meta_box() {
    add_meta_box(
        'boek_koppelingen',
        'Gekoppelde Programmeertalen',
        'toon_boek_koppelingen_meta_box',
        'boek',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'boek_koppelingen_meta_box');

// Weergave van de meta box
function toon_boek_koppelingen_meta_box($post) {
    $gekoppelde_talen = get_post_meta($post->ID, '_gekoppelde_talen', true);
    $gekoppelde_talen = is_array($gekoppelde_talen) ? $gekoppelde_talen : [];

    $talen = get_posts([
        'post_type'   => 'programmeertaal',
        'numberposts' => -1
    ]);

    echo '<p>Selecteer programmeertalen gekoppeld aan dit boek:</p>';
    foreach ($talen as $taal) {
        $checked = in_array($taal->ID, $gekoppelde_talen) ? 'checked' : '';
        echo '<label><input type="checkbox" name="gekoppelde_talen[]" value="' . $taal->ID . '" ' . $checked . '> ' . esc_html($taal->post_title) . '</label><br>';
    }
}

// Opslaan van gekoppelde programmeertalen
function sla_boek_koppelingen_op($post_id) {
    if (array_key_exists('gekoppelde_talen', $_POST)) {
        update_post_meta($post_id, '_gekoppelde_talen', $_POST['gekoppelde_talen']);
    } else {
        delete_post_meta($post_id, '_gekoppelde_talen');
    }
}
add_action('save_post_boek', 'sla_boek_koppelingen_op');

// Voeg de kolom toe aan de boek-lijst
function voeg_quick_edit_kolom_toe($columns) {
    $columns['programmeertalen'] = 'Programmeertalen';
    return $columns;
}
add_filter('manage_boek_posts_columns', 'voeg_quick_edit_kolom_toe');

// Vul de kolom met data
function vul_quick_edit_kolom($column, $post_id) {
    if ($column === 'programmeertalen') {
        $gekoppelde_talen = get_post_meta($post_id, '_gekoppelde_talen', true);
        if (!empty($gekoppelde_talen)) {
            $namen = array_map(function($id) {
                return get_the_title($id);
            }, $gekoppelde_talen);
            echo implode(', ', $namen);
        } else {
            echo 'Geen';
        }
    }
}
add_action('manage_boek_posts_custom_column', 'vul_quick_edit_kolom', 10, 2);

// Voeg Quick Edit functionaliteit toe
function voeg_quick_edit_form_toe($column_name, $post_type) {
    if ($post_type !== 'boek' || $column_name !== 'programmeertalen') {
        return;
    }

    $talen = get_posts([
        'post_type'   => 'programmeertaal',
        'numberposts' => -1
    ]);

    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title">Programmeertalen</span>
                </label>
                <div class="checkbox-container">
                    <?php foreach ($talen as $taal): ?>
                        <label>
                            <input type="checkbox" class="quick-edit-taal" value="<?php echo $taal->ID; ?>">
                            <?php echo esc_html($taal->post_title); ?>
                        </label><br>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </fieldset>
    <?php
}
add_action('quick_edit_custom_box', 'voeg_quick_edit_form_toe', 10, 2);


// Voeg JavaScript toe voor Quick Edit
function quick_edit_scripts($hook) {
    if ($hook !== 'edit.php') return;

    ?>
    <script>
        jQuery(document).ready(function($) {
            // ✅ Haal data op als je Quick Edit opent
            $(document).on('click', '.editinline', function() {
                let post_id = $(this).closest('tr').find('.quick-edit-programmeertalen').data('post-id');
                let talen = $(this).closest('tr').find('.quick-edit-programmeertalen').data('talen');

                if (talen && Array.isArray(talen)) {
                    $('.quick-edit-taal').prop('checked', false);
                    talen.forEach(id => {
                        $('.quick-edit-taal[value="' + id + '"]').prop('checked', true);
                    });
                }

                // Voeg post ID toe aan de quick edit form
                $('input[name="quick_edit_post_id"]').val(post_id);
            });

            // ✅ Sla data op als je Quick Edit opslaat
            $('#bulk_edit').on('click', function() {
                let post_id = $('input[name="quick_edit_post_id"]').val();
                if (!post_id) return;

                let geselecteerde_talen = [];
                $('.quick-edit-taal:checked').each(function() {
                    geselecteerde_talen.push($(this).val());
                });

                let data = {
                    action: 'sla_quick_edit_op',
                    post_id: post_id,
                    talen: geselecteerde_talen,
                    security: '<?php echo wp_create_nonce("quick_edit_nonce"); ?>'
                };

                $.post(ajaxurl, data, function(response) {
                    location.reload();
                });
            });
        });
    </script>
    <?php
}
add_action('admin_footer', 'quick_edit_scripts');


// Opslaan van Quick Edit data
function sla_quick_edit_op() {
    check_ajax_referer('quick_edit_nonce', 'security');

    if (!current_user_can('edit_posts')) {
        wp_send_json_error('Geen rechten');
    }

    $post_id = intval($_POST['post_id']);
    $talen = isset($_POST['talen']) ? array_map('intval', $_POST['talen']) : [];

    if ($post_id) {
        update_post_meta($post_id, '_gekoppelde_talen', $talen);
        wp_send_json_success();
    }

    wp_send_json_error('Fout bij opslaan');
}
add_action('wp_ajax_sla_quick_edit_op', 'sla_quick_edit_op');
