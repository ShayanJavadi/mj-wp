<?php
// Solo Framework table text component
if ( ! defined( 'ABSPATH' ) ) exit;

class SLFTableSelect extends SLFTableComponent {


	public function __construct( $title, $name, $value, $data_source ) {
		parent::__construct( $title, $name, $value, null );
		$this->data_source = $data_source;
	}
	
    public function renderCell(){
    	if ( $this->name == 'time'){
				$format = get_option( 'time_format' );
				return date_i18n( $format,   $this->value );  	
    	}
		return $this->value;    	
    }
    public function renderControl(){
    	 

    	$html = '<label class="slf_table_component_label" >' . $this->title . '</label>';
		$html .= '<select class="slf_table_component_select slf_table_component_input" name="' . $this->name . '" data-type="select" data-init="' . $this->value . '"  >';

		$data_source = $this->data_source;
		$source_class = $data_source[0][0];
	   	$source_function = $data_source[0][1];
		$source_condition = $data_source[1];
 
	 	$options = $source_class::$source_function( $source_condition );
 
	 	foreach( $options as $key => $value ){
	 		$selected = '';
	 		if( $key == $this->value  ){
	 			$selected = ' selected ';
	 		}

			$html .= '<option data-ext="' . $value[1] . '" ' . $selected . ' value="' . $key . '" >' . $value[0] . '</option>';
	 	}
 
		$html .= '</select>';
		return $html;
    }


}
