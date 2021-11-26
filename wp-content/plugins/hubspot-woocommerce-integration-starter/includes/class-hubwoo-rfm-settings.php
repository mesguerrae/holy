<?php
/*
    settings template for RFM ratings to be used over HubSpot
*/

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class RFM_Configuration extends WP_List_Table{

    public function prepare_items() {

        $perPage = 10;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $data = $this->table_data();
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        $this->items = $data;
    }

    public function get_columns() {
        
        $columns = array(
            'score'     => __( 'Score', 'hubwoo' ) . "<p>(" . __( "ratings for rfm segmentation", "hubwoo" ) . ")</p>",
            'recency'   => __( 'Recency', 'hubwoo' ) . "<p>(" . __( "days since last order", "hubwoo" ) . ")</p>",
            'frequency' => __( 'Frequency', 'hubwoo' ) . "<p>(" . __( "total orders placed", "hubwoo" ) . ")</p>",
            'monetary'  => __( 'Monetary', 'hubwoo' ) . "<p>(" . __( "total money spent", "hubwoo" ) . ")</p>"
        );
        return $columns;
    }

    private function table_data() {
       
        $temp_data = array();

        $rfm_settings = array( "score_5" => 5, "score_4" => 4, "score_3" => 3, "score_2" => 2, "score_1" => 1 );
        $hubwoo_rfm_at_5    = get_option( "hubwoo_rfm_5", array( 0 => 30, 1 => 20, 2 => 1000 ) );
        $hubwoo_from_rfm_4  = get_option( "hubwoo_from_rfm_4", array( 0 => 31, 1 => 10, 2 => 750 ) );
        $hubwoo_to_rfm_4    = get_option( "hubwoo_to_rfm_4", array( 0 => 90, 1 => 20, 2 => 1000 ) );
        $hubwoo_from_rfm_3  = get_option( "hubwoo_from_rfm_3", array( 0 => 91, 1 => 5, 2 => 500 ) );
        $hubwoo_to_rfm_3    = get_option( "hubwoo_to_rfm_3", array( 0 => 180, 1 => 10, 2 => 750 ) );
        $hubwoo_from_rfm_2  = get_option( "hubwoo_from_rfm_2", array( 0 => 181, 1 => 2, 2 => 250 ) );
        $hubwoo_to_rfm_2    = get_option( "hubwoo_to_rfm_2", array( 0 => 360, 1 => 5, 2 => 500 ) );
        $hubwoo_rfm_at_1    = get_option( "hubwoo_rfm_1", array( 0 => 361, 1 => 2, 2 => 250 ) );

        foreach ( $rfm_settings as $key => $single_setting ) {

            if( $single_setting == 5 ){
                $new_data = array(
                    'score'     => '<h2>'.$single_setting.'</h2>',
                    'recency'   => '<p><span>'.__("Less than", "hubwoo").'</span><input size="5" type="number" name="hubwoo_rfm_5[]" value = "'.$hubwoo_rfm_at_5[0].'"></p>',
                    'frequency' => '<p><span>'.__("More than", "hubwoo").'</span><input size="5" type="number" name="hubwoo_rfm_5[]" value = "'.$hubwoo_rfm_at_5[1].'"></p>',
                    'monetary'  => '<p><span>'.__("More than", "hubwoo").'</span><input size="5" type="number" name="hubwoo_rfm_5[]" value = "'.$hubwoo_rfm_at_5[2].'"></p>',
                );
            }
            elseif( $single_setting == 1 ){
                $new_data = array(
                    'score'    => "<h2>".$single_setting."</h2>",
                    'recency'   => '<p><span>'.__("More than", "hubwoo").'</span><input size="5" type="number" name="hubwoo_rfm_1[]" value="'.$hubwoo_rfm_at_1[0].'"></p>' ,
                    'frequency'  => '<p><span>'.__("Less than", "hubwoo").'</span><input size="5" type="number" name="hubwoo_rfm_1[]" value="'.$hubwoo_rfm_at_1[1].'"></p>' ,
                    'monetary' => '<p><span>'.__("Less than", "hubwoo").'</span><input size="5" type="number" name="hubwoo_rfm_1[]" value="'.$hubwoo_rfm_at_1[2].'"></p>',
                );
            }
            else{
                if( $single_setting == 4 ){
                    $rfm_from_0 = $hubwoo_from_rfm_4[0];
                    $rfm_from_1 = $hubwoo_from_rfm_4[1];
                    $rfm_from_2 = $hubwoo_from_rfm_4[2];
                    $rfm_to_0 = $hubwoo_to_rfm_4[0];
                    $rfm_to_1 = $hubwoo_to_rfm_4[1];
                    $rfm_to_2 = $hubwoo_to_rfm_4[2];
                }elseif( $single_setting == 3 ){
                    $rfm_from_0 = $hubwoo_from_rfm_3[0];
                    $rfm_from_1 = $hubwoo_from_rfm_3[1];
                    $rfm_from_2 = $hubwoo_from_rfm_3[2];
                    $rfm_to_0 = $hubwoo_to_rfm_3[0];
                    $rfm_to_1 = $hubwoo_to_rfm_3[1];
                    $rfm_to_2 = $hubwoo_to_rfm_3[2];
                }
                elseif( $single_setting == 2 ){
                    $rfm_from_0 = $hubwoo_from_rfm_2[0];
                    $rfm_from_1 = $hubwoo_from_rfm_2[1];
                    $rfm_from_2 = $hubwoo_from_rfm_2[2];
                    $rfm_to_0 = $hubwoo_to_rfm_2[0];
                    $rfm_to_1 = $hubwoo_to_rfm_2[1];
                    $rfm_to_2 = $hubwoo_to_rfm_2[2];
                }
                $new_data = array(
                    'score'    => "<h2>".$single_setting."</h2>",
                    'recency'   => '<p><span>'.__("From", "hubwoo").'</span><input size="5" type="number" name="hubwoo_from_rfm_'.$single_setting.'[]" value="'.$rfm_from_0.'"></p><p><span>'.__("To", "hubwoo").'</span><input size="5" type="number" name="hubwoo_to_rfm_'.$single_setting.'[]" value="'.$rfm_to_0.'"></p>' ,
                    'frequency'  => '<p><span>'.__("From", "hubwoo").'</span><input size="5" type="number" name="hubwoo_from_rfm_'.$single_setting.'[]" value="'.$rfm_from_1.'"></p><p><span>'.__("To", "hubwoo").'</span><input size="5" type="number" name="hubwoo_to_rfm_'.$single_setting.'[]" value="'.$rfm_to_1.'"></p>',
                    'monetary' => '<p><span>'.__("From", "hubwoo").'</span><input size="5" type="number" name="hubwoo_from_rfm_'.$single_setting.'[]" value="'.$rfm_from_2.'"></p><p><span>'.__("To", "hubwoo").'</span><input size="5" type="number" name="hubwoo_to_rfm_'.$single_setting.'[]" value="'.$rfm_to_2.'"></p>' ,
                );
            }
            $temp_data[] = $new_data;
        }
        return $temp_data;
    }

    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'score':
            case 'recency':
            case 'frequency':
            case 'monetary':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }
    public function display_rows_or_placeholder() {
        if ( $this->has_items() ) {
            $this->display_rows();
        } else {
            echo '<tr class="no-items"><td class="colspanchange" colspan="' . $this->get_column_count() . '">';
            $this->no_items();
            echo '</td></tr>';
        }
    }
}

?>