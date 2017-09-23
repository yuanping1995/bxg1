<?php
namespace app\admin\controller;
use think\Db;
use think\Reinputuest;
class Zhuan extends Base
{
    public function index()
    {
		if(! \think\Request::instance()->isPost())
		{
				$old_id = input('old_id');
				$new_id = input('new_id');
				if(!empty(Db::name('goods')->where('goods_id',$old_id)->find()))
				{
					
				Db::startTrans();
				try{
					if(Db::name('goods')->where('goods_id',$old_id)->update(['goods_id'=>$new_id]))
					{
						Db::name('goods_content')->where('goods_id',$new_id)->delete();
						echo '改变详情页：';
						if(Db::name('goods_content')->where('goods_id',$old_id)->update(['goods_id'=>$new_id]))
							echo "成功";
						echo "<br/>";
						echo '改变主图：';
						Db::name('goods_img')->where('goods_id',$new_id)->delete();
						if(Db::name('goods_img')->where('goods_id',$old_id)->update(['goods_id'=>$new_id]))
							echo "成功";
						echo "<br/>";
						echo '改变规格：';
						Db::name('spec_goods_price')->where('goods_id',$new_id)->delete();
						if(Db::name('spec_goods_price')->where('goods_id',$old_id)->update(['goods_id'=>$new_id]))
						echo "成功";
						echo "<br/>";
						echo '改变规格图（非必须成功）：';
						Db::name('spec_image')->where('goods_id',$new_id)->delete();
						if(Db::name('spec_image')->where('goods_id',$old_id)->update(['goods_id'=>$new_id]))
						echo "成功";
						echo "<br/>";
						echo '执行完成<br/>';
						echo '<a href="javascript:" onclick="self.location=document.referrer;">返回继续</a>';
					}
					Db::commit();    
				} catch (\Exception $e) {
					 echo '出错了';
					Db::rollback();
				}
			 }else{
				 echo '存在这个商品呢';
			 }
		}else{
			$this->fetch('');
		}
			
		die;
       
	}

}