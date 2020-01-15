<?php

namespace DuxCms\Model;

use Think\Model;

/**
 * 文件操作
 */
class FileModel extends Model {
	// 完成
	protected $_auto = array (
			array (
					'time',
					'time',
					3,
					'function' 
			) 
	);
	
	/**
	 * 上传数据
	 * 
	 * @param array $files
	 *        	上传$_FILES信息
	 * @param array $config
	 *        	上传配置信息可选
	 * @return array 文件信息
	 */
	public function uploadData($files, $config = array()) {
	
		// 上传
		$upload = new \Think\Upload ( $config, 'Local' );
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','mp4','mov','3gp','wmv','avi','ogg');
		$info = $upload->upload ( $files );
		$info = current ( $info );

		if ($info) {
			// 记录文件信息
			$fileDir = 'Uploads/' . $info ['savepath'];
			$file = $fileDir . $info ['savename'];
			$info ['title'] = $info ['name'];
			$info ['original'] = __ROOT__ . '/' . $file;
			// 处理图片数据
			$imgType = array (
					'jpg',
					'jpeg',
					'png',
					'gif',
					'bmp' 
			);

			if (in_array ( strtolower($info ['ext']), $imgType )) {
				
               if(function_exists('exif_read_data')){
	               	$exif = exif_read_data(ROOT_PATH.$file);
	               	if(!empty($exif['Orientation'])) {
	               		switch($exif['Orientation']) {
	               			case 8:
	               				$this->flip(ROOT_PATH.$file, ROOT_PATH.$file,90);
	               				break;
	               			case 3:
	               				$this->flip(ROOT_PATH.$file, ROOT_PATH.$file,180);
	               				break;
	               			case 6;
	               			$this->flip(ROOT_PATH.$file, ROOT_PATH.$file,-90);
	               			break;
	               		}
	               	}
               }
				
			
				//裁剪
				if(strtolower($info ['ext'])!='gif') $this->cut_photo($file);
				
				// 设置图片驱动
				$image = new \Think\Image ();
				// 设置缩图
				if (C ( 'THUMB_STATUS' )&&strtolower($info ['ext'])!='gif') {
					$image->open ( ROOT_PATH . $file );
					$thumbFile = $fileDir . 'thumb_' . $info ['savename'];
					$status = $image->thumb ( C ( 'THUMB_WIDTH' ), C ( 'THUMB_HEIGHT' ), C ( 'THUMB_TYPE' ) )->save ( ROOT_PATH . $thumbFile );
					if ($status) {
						$file = $thumbFile;
					}

				}
				// 设置水印
				if (C ( 'WATER_STATUS' )) {
					$image->open ( ROOT_PATH . $file );
					$wateFile = $fileDir . 'wate_' . $info ['savename'];
					$status = $image->water ( ROOT_PATH . 'Public/watermark/' . C ( 'WATER_IMAGE' ), C ( 'WATER_POSITION' ) )->save ( ROOT_PATH . $wateFile );
					if ($status) {
						$file = $wateFile;
					}
				}
			}
			$info ['url'] = __ROOT__ . '/' . $file;
			
			//获取视频封面图
// 			if (in_array ( strtolower($info ['ext']), array('mp4','mov','3gp','wmv','avi','ogg') )){
// 				$savename = str_replace($info ['ext'], 'jpg', $info ['savename']);
// 				$output = $fileDir . 'video_' . $savename; 
// 				convertToFlv( ROOT_PATH.$file,ROOT_PATH.$output );				
// 				$info ['video_url'] = __ROOT__ . '/' .$output; 
// 			}
			
			//接入oos
			if(C ( 'open_oss' ) > 0){
				if($info ['video_url']) $info ['video_url'] = $this->oos_upimg($info ['video_url'],$info ['video_url']);
				$info ['url'] = $this->oos_upimg($info ['url'],$info ['original']);
				$info ['original'] = $info ['url'];
			}

			// 入库文件信息
			$this->create ( $info );
			$this->add ();
			return $info;
		} else {
			$this->error = $upload->getError ();
			return false;
		}
	}
	
	
	public function cut_photo($file){
		$image = new \Think\Image();
		$image->open(ROOT_PATH . $file);
		$width = $image->width();
		$height =$image-> height();
		// if($width>420){
		// 	$status = $image->thumb(420, $height, 1)->save(ROOT_PATH . $file);
		// }
		 
		return $file;
	}
	
	
	// 上传图片到阿里云OOS
	public function oos_upimg($url,$original) {

		if (C ( 'open_oss' ) > 0 && C ( 'OSS_ACCESS_ID' ) && C ( 'OSS_ACCESS_KEY' ) && C ( 'OSS_ENDPOINT' ) && C ( 'OSS_TEST_BUCKET' ) && C ( 'OSS_URL' )) {
			if(empty($url)) return false;
			$ourl = ROOT_PATH .substr($url, 1);
			$original = ROOT_PATH .substr($original, 1);
			$url = 'https://' . $_SERVER ['HTTP_HOST'] . $url;
			require_once ROOT_PATH . "OOS_SDK/samples/Common.php";
			$type = $this->get_extension ( $url );
			if (! $type)
				return false;
			
			$tools = new \Common ();
			$root = date ( 'Y-m-d', time () );
			$root2 = date ( 'H', time () );
			$rand = rand ( 1000, 9999 );
			$name = md5 ( date ( 'YmdHis', time () ) . $rand );
			$type_name = in_array ( strtolower($type), array('mp4','mov','3gp','wmv','avi','ogg') )?'video':'image';
			
			$filename = 'shanmaosplb/'.$type_name.'/' . $root . '/' . $root2 . '/' . $name . '.' . $type;
			$bucket = $tools::getBucketName ();
			$ossClient = $tools::getOssClient ();
			if (is_null ( $ossClient ))
				return false;

			$ossClient->uploadFile ( $bucket, $filename, $ourl );
			
			$doesExist = $ossClient->doesObjectExist ( $bucket, $filename );
			if ($doesExist) {
				if (file_exists ( $ourl )) {
					$isdel = @unlink ( $ourl );
				}
				if (file_exists ( $original )){
					$isdel = @unlink ( $original );
				}

				return $tools::bucketURL . $filename;
			} else {
				
				return false;
			}
		} else {
			return $url;
		}
	}
	
	// 删除文件
	public function oos_del($url) {
		$arr = parse_url ( $url );
		$object = substr ( $arr ['path'], 1, strlen ( $arr ['path'] ) );
		if (C ( 'open_oss' ) > 0 && C ( 'OSS_ACCESS_ID' ) && C ( 'OSS_ACCESS_KEY' ) && C ( 'OSS_ENDPOINT' ) && C ( 'OSS_TEST_BUCKET' ) && C ( 'OSS_URL' )) {
			require_once ROOT_PATH . "OOS_SDK/samples/Common.php";
			$tools = new \Common ();
			$bucket = $tools::getBucketName ();
			$ossClient = $tools::getOssClient ();
			if (is_null ( $ossClient ))
				return false;
			
			$doesExist = $ossClient->doesObjectExist ( $bucket, $object );
			if ($doesExist) {
				try {
					$re = $ossClient->deleteObject ( $bucket, $object );
				} catch ( OssException $e ) {
					printf ( __FUNCTION__ . ": FAILED\n" );
					printf ( $e->getMessage () . "\n" );
					return;
				}
			} else {
				
				return false;
			}
			return false;
		}
	}
	
	
	
	
	/**
	 * 修改一个图片 让其翻转指定度数
	 *
	 * @param string $filename 文件名（包括文件路径）
	 * @param float $degrees 旋转度数
	 * @return boolean
	 * @author zhaocj
	 */
	public function flip($filename,$src,$degrees = 270)
	{
		//读取图片
		$data = @getimagesize($filename);
	
		if($data==false)return false;
		//读取旧图片
		switch ($data[2]) {
			case 1:
				$src_f = imagecreatefromgif($filename);break;
			case 2:
				$src_f = imagecreatefromjpeg($filename);break;
			case 3:
				$src_f = imagecreatefrompng($filename);break;
		}
		if($src_f=="")return false;
		$rotate = @imagerotate($src_f, $degrees,0);
	
		if(!imagejpeg($rotate,$src,100))return false;
		@imagedestroy($rotate);
		return true;
	}
  
  //获取文件后缀
public function get_extension($file)
{
	return pathinfo($file, PATHINFO_EXTENSION);
}
	
}
