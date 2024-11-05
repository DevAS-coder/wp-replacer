<?php
/**
 * Plugin Name: Word Replacer
 * Description: You  can replace Words With this Plugin
 * Version: 1.0
 * Author: DevAS
 */

if (!defined('ABSPATH')) {
    exit;
}

function create_table_replacer() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'wpreplacer';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        old_word varchar(255) NOT NULL,
        new_word varchar(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}

// activate hook
register_activation_hook(__FILE__, 'create_table_replacer');


add_action('admin_menu', 'my_custom_plugin_create_menu');

function my_custom_plugin_create_menu() {
    add_menu_page(
        'Word Replacer', 
        'Word Replacer',
        'manage_options',
        'Word-Replacer', 
        'word_replacer_settings_page', 
        'dashicons-admin-generic', 
        90 
    );
}

if (isset($_POST['submitbtn'])){

    $oldword = $_POST['old_word'];
    $newword = $_POST['new_word'];

    global $wpdb;
    $table_name = $wpdb->prefix . 'wpreplacer';
  
    $wpdb->insert($table_name, array(
        'old_word' => $_POST['old_word'],
        'new_word' => $_POST['new_word'],
    ));
    
}

function word_replacer_settings_page() {
    ?>

        <div>
            <?php
            if (isset($_POST['submitbtn'])){
                echo '<h3>Succesfully added</h3>';
            }
            if (isset($_POST['id'])) {
                
                $buttonId = intval($_POST['id']);

                global $wpdb;
                $table_name = $wpdb->prefix . 'wpreplacer';
                $wpdb->delete( $table_name, array( 'id' => $buttonId ), array( '%d' ) );

                if ($wpdb->delete( $table_name, array( 'id' => $buttonId ), array( '%d' ) )) {
                    echo "<h3>Record deleted successfully</h3>";
                } else {
                    echo "Error deleting record: ";
                }
            }

            ?>
            <h1>Word Raplacer Plugin</h1>
            <br>
            <form method="post" action="#">
                <input type="text" id="old_word" name="old_word"><label style="font-size: x-large;" for="old_word"> : Old Word</label><br><br>
                <input type="text" id="new_word" name="new_word"><label style="font-size: x-large;" for="new_word"> : New Word</label><br><br>
                <input type="submit" value="Save Settings" name="submitbtn" style="background-color: cyan; padding: 10px; border-radius:20px;">
            </form>
        
        </div>
   
    
    <br><br>
    <style>
       td, th {
      border: 1px solid #dddddd;
      text-align: right;
      padding: 8px;
    }
    tr:nth-child(even) {
      background-color: #dddddd;
    }
    table{
       border-collapse: collapse;
       width: 80%;
    }
    </style>
    <form method="post" action="#">
    <table>
       <th>id</th>
       <th>old words</th>
       <th>new words</th>
       <th>Delete</th>
       <tr>
    <?php
    $connect = mysqli_connect('localhost', 'root', '', 'PFWP');

    $result = ['0'];
    $counter = 0;
    global $wpdb;
    while ($result != []){
        $sql_query = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}wpreplacer LIMIT 1 OFFSET $counter", 
            $counter
        );
        $result = $wpdb->get_row($sql_query, ARRAY_A);
    
        if ($result != []){
            ?>
            <td><?php echo $id = $result['id']; ?></td>
            <td><?php echo $oldword = $result['old_word']; ?></td>
            <td><?php echo $newword = $result['new_word']; ?></td>
            <td><button type="submit" name="id" value="<?php echo $id ?>">حذف</button>
        </tr>
        <?php
        $counter++;
        }
        else{
            break;
        }
    }
    
    ?>
    </table>
    </form>
    
    <?php
}


include_once 'replacer.php';