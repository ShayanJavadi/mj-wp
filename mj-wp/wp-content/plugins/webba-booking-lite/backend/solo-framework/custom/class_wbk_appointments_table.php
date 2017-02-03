<?php
//WBK appointment table class
// check if accessed directly
add_action( 'wp_ajax_wbk_get_free_time_for_day',  'wbkGetFreeTimeForDay' ); 
function wbkGetFreeTimeForDay(){
    $date = sanitize_text_field( $_POST['date'] );
    $appointment_id = sanitize_text_field( $_POST['appointment_id'] );
    $service_id = sanitize_text_field( $_POST['service_id'] );
    if( !is_numeric( $appointment_id ) || !is_numeric( $service_id ) ){
        echo  $appointment_id.'-'. $service_id;
        wp_die();
        return;
    }
    $date = strtotime( $date );
    if( $date == FALSE  ){
        echo '-1';
        wp_die();
        return;
    }
    $service_schedule = new WBK_Service_Schedule();
    if ( !$service_schedule->setServiceId( $service_id ) ){
        echo '-2';
        wp_die();
        return;
    }
    if ( !$service_schedule->load() ){
        echo '-3';
        wp_die();
        return;    
    }    
    if( $service_schedule->getDayStatus( $date ) == 0 ){
        $html = '<option data-ext="0"   value="0" >' . __( 'Free time slots not found', 'wbk' ) . '</option>';
        echo $html;
        wp_die();
        return;
    }
    $service_schedule->buildSchedule( $date );
    $options = $service_schedule->getFreeTimeslotsPlusGivenAppointment( $appointment_id );
    $html = '';
    foreach( $options as $key => $value ){
        $html .= '<option data-ext="' . $value[1] . '"  value="' . $key . '" >' . $value[0] . '</option>';
    }
    echo $html;
    wp_die();
    return;
}
if ( ! defined( 'ABSPATH' ) ) exit;
class WBK_Appointments_Table extends SLFTable {
	public function __construct() {
			$this->field_set = new SLFFieldSet( true, true );
            $field = new SLFField( array( 'title' => __( 'Service','wbk' ),     
                                         'name' => 'service_id',
                                         'format' => '%d',
                                         'component' => 'SLFTableWbkService',
                                         'render_cell' => true,
                                         'render_control' => true,
                                         'validation' => array( array( 'SLFValidator', 'checkInteger' ), array( 1, 10000 ) )

                                          )
                                 );
            $this->field_set->append( $field );
            $field = new SLFField( array( 'title' => __( 'Date','wbk' ),     
                                         'name' => 'day',
                                         'format' => '%d',
                                         'component' => 'SLFTableDate',
                                         'render_cell' => true,
                                         'render_control' => true,
                                         'validation' => array( array( 'SLFValidator', 'checkDate' ), array( 0, 0 ) )
                                        )
                                 );
            $this->field_set->append( $field );
            $field = new SLFField( array( 'title' => __( 'Time','wbk' ),     
                                         'name' => 'time',
                                         'format' => '%d',
                                         'component' => 'SLFTableSelect',
                                         'render_cell' => true,
                                         'render_control' => true,
                                         'data_source' => array( 'WBK_Db_Utils', 'getFreeTimeslotsArray' ),
                                         'validation' => array( array( 'SLFValidator', 'checkInteger' ), array( 1481270915, 4132022915 ) )
                                          )
                                 );

            $this->field_set->append( $field );
            $field = new SLFField( array('title' => __( 'Places booked', 'wbk' ),     
                                         'name' => 'quantity',
                                         'format' => '%d',
                                         'component' => 'SLFTableSelect',
                                         'assoc' => 'time',
                                         'render_cell' => true,
                                         'render_control' => true,
                                         'data_source' => array( 'WBK_Db_Utils', 'blankArray' ),
                                         'validation' => array( array( 'SLFValidator', 'checkInteger' ), array( 1, 100000 ) )
                                          )
                                 );
            $this->field_set->append( $field );
            $field = new SLFField( array( 'title' => __( 'Customer name','wbk' ),     
                                         'name' => 'name',
                                         'format' => '%s',
                                         'component' => 'SLFTableText',
                                         'render_cell' => true,
                                         'render_control' => true,
                                         'validation' => array( array( 'SLFValidator', 'checkText' ), array( 3, 128 ) )
                                          )
                                 );
            $this->field_set->append( $field );
            $field = new SLFField( array('title' => __( 'Customer email', 'wbk' ),     
                                         'name' => 'email',
                                         'format' => '%s',
                                         'component' => 'SLFTableText',
                                         'render_cell' => true,
                                         'render_control' => true,
                                         'validation' => array( array( 'SLFValidator', 'checkEmail' ), array( 0, 0 ) )
                                          )
                                 );
            $this->field_set->append( $field );
            $field = new SLFField( array('title' => __( 'Customer phone', 'wbk' ),     
                                         'name' => 'phone',
                                         'format' => '%s',
                                         'component' => 'SLFTableText',
                                         'render_cell' => true,
                                         'render_control' => true,
                                         'validation' => array( array( 'SLFValidator', 'checkText' ), array( 3, 30 ) )
                                          )
                                 );
            $this->field_set->append( $field );            
            $field = new SLFField( array('title' => __( 'Customer comment', 'wbk' ),     
                                         'name' => 'description',
                                         'format' => '%s',
                                         'component' => 'SLFTableText',
                                          'render_cell' => true,
                                         'render_control' => false

                                          )
                                 );
            $this->field_set->append( $field );
            $field = new SLFField( array('title' => __( 'Custom fields', 'wbk' ),     
                                         'name' => 'extra',
                                         'format' => '%s',
                                         'component' => 'SLFTableWbkCustomField',      
                                         'render_cell' => true,
                                         'render_control' => false,
                                          )
                                 );
            $this->field_set->append( $field ); 
            $field = new SLFField( array('title' => __( 'Duration', 'wbk' ),     
                                         'name' => 'duration',
                                         'format' => '%d',
                                         'component' => 'SLFTableHiddenText',      
                                         'render_cell' => false,
                                         'render_control' => true,
                                          )
                                 );
            $this->field_set->append( $field );
            $this->table_name = 'wbk_appointments';
            $this->filter_set = array();
            $filter = new SLFTableFilterDateRange( __( 'Select date range:', 'wbk' ), 'day' );
            $filter->setDefault();
            $this->filter_set['day'] = $filter;
            $filter = new WBKTableFilterServices( __( 'Select services:', 'wbk' ), 'service_id' );
            $filter->setDefault();
            $this->filter_set['service_id'] = $filter;
	}
    public function onTableAddRow( $id ){
        $auto_lock = get_option( 'wbk_appointments_auto_lock', 'disabled' );
        if ( $auto_lock == 'enabled' ){
            $service_id = WBK_Db_Utils::getServiceIdByAppointmentId( $id );
            WBK_Db_Utils::lockTimeSlotsOfOthersServices( $service_id, $id );
        }
    }
    public function checkAccess(){
        return TRUE;
    }
}