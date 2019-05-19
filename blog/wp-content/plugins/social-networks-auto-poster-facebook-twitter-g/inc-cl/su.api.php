<?php    
//## NextScripts FriendFeed Connection Class
$nxs_snapAPINts[] = array('code'=>'SU', 'lcode'=>'su', 'name'=>'StumbleUpon');

if (!class_exists('nxsAPI_SU')){class nxsAPI_SU{ var $ck = array(); var $ckey=''; var $clid='';  var $debug = false; var $proxy = array();
    
    function headers($ref, $post=false, $xhr=true){ $hdrsArr = array(); 
      if ($xhr) $hdrsArr['X-Requested-With']='XMLHttpRequest'; 
      $hdrsArr['Connection']='keep-alive'; $hdrsArr['Referer']=$ref;
      //$hdrsArr['User-Agent']='Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)';
      $hdrsArr['User-Agent']='Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)';
      if($post) $hdrsArr['Content-Type']='application/x-www-form-urlencoded'; 
      if ($xhr) $hdrsArr['Accept']='application/json, text/javascript, */*; q=0.01'; else $hdrsArr['Accept']='text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
      $hdrsArr['Origin']='https://www.stumbleupon.com';
      if (function_exists('gzdeflate')) $hdrsArr['Accept-Encoding']='gzip,deflate,sdch'; $hdrsArr['Accept-Language']='en-US,en;q=0.8'; $hdrsArr['Accept-Charset']='ISO-8859-1,utf-8;q=0.7,*;q=0.3'; return $hdrsArr;
    }    
    function check($u=''){ $ck = $this->ck; if ($this->debug) echo "[SU] Checking <br/>\r\n"; if (!empty($ck) && is_array($ck)) { $hdrsArr = $this->headers('http://www.stumbleupon.com/'); 
        $hdrsArr = $this->headers('http://www.stumbleupon.com/submit', true); if (!empty($this->ckey)) $ckAccessTokenKey = $this->ckey; else $ckAccessTokenKey = nxs_getCKVal('su_accesstoken', $ck);         
        $advSet = nxs_mkRemOptsArr($hdrsArr, $ck, '', $this->proxy);  $response = nxs_remote_get('http://www.stumbleupon.com/submit/', $advSet); if (is_nxs_error($response)) return 'Connection ERROR: '.print_r($response, true);
        /*$response['body'] = htmlentities($response['body'], ENT_COMPAT, "UTF-8");  $response['body'] = htmlentities($response['body']); */ // prr($response); // die();
        if (isset($response['headers']['location']) && $response['headers']['location']=='/submit/visitor') return 'Bad Saved Login';  
        if ( $response['response']['code']=='200' && stripos($response['body'], 'name="_token" value="')!==false && stripos($response['body'], '=&quot;/stumbler/')!==false){     
           if ($this->debug) echo "[SU] Saved login - IN...<br/>\r\n"; return true; 
        } else return false; } else return false;
      return false; 
    }    
    function connect($u,$p){ $badOut = 'Error: '; 
      //## Check if alrady IN
      if ($this->check($u)!==true){ if ($this->debug) echo "[SU] NO Saved Data; Logging in...<br/>\r\n"; $hdrsArr = $this->headers('https://www.stumbleupon.com/login', false, false); //   echo "LOGGIN";
      $response = nxs_remote_get('https://www.stumbleupon.com/login', array('headers' => $hdrsArr)); $p = substr($p, 0, 16); if (is_nxs_error($response)) return 'Connection ERROR: '.print_r($response, true);
      $contents = $response['body']; $ckArr = $response['cookies']; $tkn = CutFromTo($contents,'name="_token" id="token" value="','"'); //$response['body'] = htmlentities($response['body']);  prr($response);    die();       
      $flds  = array(); $flds['user'] = $u; $flds['pass'] = $p; $flds['_token'] = $tkn; $flds['_output'] = 'Json'; $flds['remember'] = 'true'; $flds['nativeSubmit'] = '0'; $flds['_action'] = 'auth'; $flds['_method'] = 'create';       
      $hdrsArr = $this->headers('https://www.stumbleupon.com', true, true);  $advSet = nxs_mkRemOptsArr($hdrsArr, $ckArr, $flds, $this->proxy);// prr($advSet);      
      $response = nxs_remote_post( 'https://www.stumbleupon.com/login', $advSet);  if (is_nxs_error($response)) return 'Connection ERROR 2: '.print_r($response, true);
      if ( $response['response']['code']=='302') return "Invalid username or password"; if (stripos($response['body'],',"_error":"Invalid username') !==false ) return "Invalid username or password";
      if (stripos($response['body'],'"_success":true') !==false){  if ($this->debug) echo "[SU]Login OK<br/>\r\n"; $this->ck =  nxsMergeArraysOV($ck, $response['cookies']);  return false; } else return 'Connection ERROR 3: '.print_r($response, true);
    }}
    function post($msg, $lnk, $cat, $tags, $nsfw=false){ $ck = $this->ck; if ($this->debug) echo "[SU] Posting ...".$lnk."<br/>\r\n"; $badOut = '';  $msg = str_replace("\n",'\n', str_replace("\r",'', strip_tags($msg)));
      $r2 = nxs_remote_get($lnk);  $hdrsArr = $this->headers('http://www.stumbleupon.com/submit', false, false); $advSet = nxs_mkRemOptsArr($hdrsArr, $ck, '', $this->proxy); 
      $response = nxs_remote_get('http://www.stumbleupon.com/submit',$advSet); $p = substr($p, 0, 16); if (is_nxs_error($response)) return 'Connection POST ERROR: '.print_r($response, true);
      $contents = $response['body']; $tkn = CutFromTo($contents,'name="_token" value="','"'); 
      $flds  = array(); $flds['url'] = $lnk; $flds['_token'] = $tkn; $flds['_output'] = 'Json'; $flds['language'] = 'EN'; $flds['nativeSubmit'] = '0'; $flds['_action'] = 'submitUrl'; $flds['_method'] = 'create';       
      $flds['review'] = $msg; $flds['tags'] = $cat; $flds['nsfw'] = $nsfw?'true':'false'; $flds['user-tags'] = $tags; 
      $hdrsArr = $this->headers('http://www.stumbleupon.com', true, true);  $advSet = nxs_mkRemOptsArr($hdrsArr, $ck, $flds, $this->proxy); //prr($advSet);      
      $response = nxs_remote_post( 'http://www.stumbleupon.com/submit', $advSet);  if (is_nxs_error($response)) return 'Connection ERROR 2: '.print_r($response, true); // prr($response); 
      if (stripos($response['body'],'"_success":true') !==false){ $pageID = CutFromTo($response['body'],'"publicid":"','"'); return array('isPosted'=>'1', 'postID'=>$pageID, 'postURL'=>'http://www.stumbleupon.com/su/'.$pageID.'/comments', 'pDate'=>date('Y-m-d H:i:s')); } 
        else return "ERROR".print_r($response, true);
    }    
}}


if (!class_exists("nxs_class_SNAP_SU")) { class nxs_class_SNAP_SU {
    
    var $ntCode = 'SU';
    var $ntLCode = 'su';     
    
    function doPostToNT($options, $message){ global $nxs_suCkArray; $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>'');
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; }      
      if (!isset($options['uName']) || trim($options['uPass'])=='') { $badOut['Error'] = 'Not Configured'; return $badOut; }            
      $pass = (substr($options['uPass'], 0, 5)=='g9c1a' || substr($options['uPass'], 0, 5)=='n5g9a')?nsx_doDecode(substr($options['uPass'], 5)):$options['uPass']; 
      //## Get Saved Login Info
      if (function_exists('nxs_getOption')) { $opVal = array(); $opNm = 'nxs_snap_su_'.sha1('nxs_snap_su'.$options['uName'].$options['uPass']); $opVal = nxs_getOption($opNm); if (!empty($opVal) & is_array($opVal)) $options = array_merge($options, $opVal); } 
      //## Format
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['msgFormat'], $message);  $urlToGo = (!empty($message['url']))?$message['url']:''; $tags = $message['tags'];
      
      $nt = new nxsAPI_SU(); if (!empty($options['ck'])) $nt->ck = $options['ck'];  $loginErr = $nt->connect($options['uName'], $pass);   
      if (!$loginErr) $ret = $nt->post($msg, $urlToGo, $options['suCat'], $tags, $options['nsfw']=='1');  else { $badOut['Error'] .= 'Something went wrong - '.print_r($loginErr, true); $ret = $badOut; }      
      //## Expired Login Info
      if (!is_array($ret) && stripos($ret,'Invalid token')!==false){ $nt->ck = array(); $loginErr = $nt->connect($options['uName'], $pass); 
        if (!$loginErr) $ret = $nt->post($msg, $urlToGo, $options['suCat'], $tags, $options['nsfw']=='1');  else { $badOut['Error'] .= 'Something went wrong - '.print_r($loginErr, true); $ret = $badOut; }       
      }
      //## Save Login Info
      if (function_exists('nxs_saveOption')) { if (empty($opVal['ck'])) $opVal['ck'] = ''; if (is_array($ret) && $ret['isPosted']=='1' && $opVal['ck'] != $nt->ck) { $opVal['ck'] = $nt->ck; nxs_saveOption($opNm, $opVal); } }      
      return $ret;
   } 
}}
?>