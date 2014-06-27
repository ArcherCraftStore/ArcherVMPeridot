<?php
class awpm_plugin_list extends WP_List_Table 
{
        
    function __construct(){
        parent::__construct(array(
                            'singular'  => __('Plugins','awpm'),     
                            'plural'    => __('plugin','awpm'),    
                            'ajax'      => false        
                            ));

    }
    function column_default($item, $column_name){
        switch($column_name){
            case 'status':
                if($item['file']!=AWPM_PLUGIN_FILE){
                    $activation_link='<div class="ct">';
                    $activation_link.='<a href="javascript:" plugin="' . $item['file'] . '"' ;
                    $activation_link.=' class="activate_btn row-title">Activate</a>';
                    $activation_link.="&nbsp;&nbsp;" . '<img src="' .  admin_url() . 'images/wpspin_light.gif" class="loading" />';
                    $activation_link.='</div>';
                    $deactivation_link='<div class="ct">';
                    $deactivation_link.='<a href="javascript:" plugin="' . $item['file'] . '"';
                    $deactivation_link.=' class="deactivate_btn row-title">Deactivate</a>';
                    $deactivation_link.="&nbsp;&nbsp;" . '<img src="' .  admin_url() . 'images/wpspin_light.gif" class="loading" />';
                    $deactivation_link.='</div>';
                    return ((is_plugin_active($item['file'])==TRUE)?$deactivation_link:$activation_link);
                }else{
                    return sprintf(__('You Can Not Perform any Action For this Plugin From Here.Go to <a href="%s">Plugins</a> Page','awpm'),admin_url('network/plugins.php'));
                }
                break;
            case 'plugin_name':
                return $item['Name'];
                break;
            case 'description':
                return $item['Description'];
                break;
            }
    } 
            
    function get_columns(){
        $columns = array(
                        'plugin_name'=>__('Plugin','awpm'),
                        'description'=>__('Description','awpm'),
                        'status'=>__('Status','awpm'),
                        );
        return $columns;
    }
                    
    function prepare_items($config){
            $columns  = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden,$sortable);
            $this->items = $config['data'];
    }
            
    function column_cb($item) {
        return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />','activity',$item->activity_id);
    }
}

