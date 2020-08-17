<?php 

// Ali Can Tellioğlu < ali@actspot.net >
class actspot extends apifunctions {





    public function __construct($apikey,$apisecret,$header){
    
        $this->actspotapiheader=$header;
        
        $this->apikey=$apikey;
        $this->apisecret=$apisecret;
    
    }


    public function run($action,$params = null){
        
        
        if($this->auth()['result'] != 'failed' && $this->auth() == 'authed'){
            
            return $this->response('success','Doğrulama başarılı',$this->$action($params));
        
        }else{
            
            return $this->auth();
            
        }
    
    }
    
    
    
    
    private function auth(){
    
        if(!is_null($this->actspotapiheader)){
            
//             $elements = explode(':', $this->actspotapiheader);
            $elements = explode(':', $this->sanitize($this->actspotapiheader, array(':')));
            if( $elements[0] != $this->apikey or $elements[1] != $this->apisecret){
            
                return $this->response('failed','Doğrulama hatası','');
                
            }else{
            
                return 'authed';
                
            }
            
        }else{
//             exec( 'logger '.json_encode( array( "header"=> $this->actspotapiheader, "server"=>$_SERVER ) ).'');
            return $this->response('failed','Dogrulama hatasi','');
        }
        
    
    
    
    
    }


    public static function sanitize($input, $allowed = array(), $__recurse_count = 0, $__recurse_limit = 10) {
        
        if ($__recurse_count > $__recurse_limit) {
            throw new \Exception('FATAL: recusion limit reached in sanitize()');
        }
        $allow = null;
        if (!empty($allowed)) {
            foreach ($allowed as $value) {
                $allow .= "\\$value";
            }
        }
        if (is_array($input)) {
            $cleaned = array();
            foreach ($input as $key => $clean) {
                $cleaned[$key] = $this->sanitize($clean, $allowed, $__recurse_count + 1);
            }
        } else {
            $cleaned = preg_replace("/[^{$allow}a-zA-Z0-9]/", '', $input);
        }
        return $cleaned;
    }    
    
    private function response($result,$message,$data){
        
        $response = array('result' =>  $result,'message' => $message,'data' => $data,'timestamp'=>date('d.m.Y H:i:s') ,'version'=>'1');
        
        return base64_encode((json_encode($response)));

    }

    


}
