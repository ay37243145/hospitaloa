<?php
declare (strict_types = 1);

namespace app\index\controller;

use app\BaseController;
use PhpOffice\PhpWord\IOFactory;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Tk extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

    /*
     * 条款解析
     * */
    public function tkjx()
    {
        return View::fetch();
    }

    /*
     * 支撑材料
     * */
    public function zccl()
    {
        return View::fetch();
    }

    /*
     * 当前条款信息
     * */
    public function tkjx_info($id)
    {
//        View::assign('id',$id);
        $k_list = Db::name('k_list')->find($id);

        $ys_title_count = Db::name('ys_list')->field('title')
            ->where('k_id',$id)->group('title')->count();

        if($ys_title_count==1){
            $c_ys_list = Db::name('ys_list')->where('k_id',$id)->select();
            View::assign('c_ys_list',$c_ys_list);
        }elseif ($ys_title_count==2){
            $c_ys_list = Db::name('ys_list')->where(['k_id'=>$id,'title'=>'C'])->select();
            $b_ys_list = Db::name('ys_list')->where(['k_id'=>$id,'title'=>'B'])->select();
            View::assign('c_ys_list',$c_ys_list);
            View::assign('b_ys_list',$b_ys_list);
        }elseif ($ys_title_count==3){
            $c_ys_list = Db::name('ys_list')->where(['k_id'=>$id,'title'=>'C'])->select();
            $b_ys_list = Db::name('ys_list')->where(['k_id'=>$id,'title'=>'B'])->select();
            $a_ys_list = Db::name('ys_list')->where(['k_id'=>$id,'title'=>'A'])->select();
            View::assign('c_ys_list',$c_ys_list);
            View::assign('b_ys_list',$b_ys_list);
            View::assign('a_ys_list',$a_ys_list);
        }

        View::assign('k_list',$k_list);
        View::assign('ys_title_count',$ys_title_count);

        return View::fetch();
    }

    /*
     * 款列表
     * */
    public function k_list(){
        $limit = Request::param('limit');
//        $limits = Request::param('limits');
        $list['data'] = Db::name('k_list')->order('id', 'asc')->paginate($limit);
        if($list){
            $list['code'] = 0;
            $list['msg'] = '请求成功';
        }else{
            $list['code'] = 1;
            $list['msg'] = '请求失败';
        }
        return json($list);
    }

    public function aa(){
        /*$ms_excel = new \COM("Excel.application")or die("不能打开Excel应用程序");
//        $word->Visible = 1;   //参数1将自动打开这个文档，0为不打开
        echo "Excel版本:{$ms_excel->Version}\n";
        $ms_excel->Visible=1;
        $ms_excel->Workbooks->open("D:\php_project/2.xlsx");*/
        /*$word = new \COM("word.application") or die("不能打开word");
        $word->Visible=True;
        $word->Documents->open("http://localhost:81/index.php/1111.docx") or die("打开失败");*/


//        return __TRAIT__.'\1111.docx';

//        $phpWord = IOFactory::load('../1111.docx');
//        $xmlWrite = IOFactory::createWriter($phpWord,'HTML');
//        $xmlWrite->save('../1111.html');
//        dump($xmlWrite);

        $word = new \COM("word.application");
        echo $word->Version;
        $word->Visible = 0;
        $word->Documents->open("D:\php_project/hospitaloa/1111.docx");
        $test=$word->ActiveDocument->content->Text;
        echo $test;


    }

    public function bb(){
        $phpWord = IOFactory::load('../1111.docx');
        dump($phpWord);
        $html = '';
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $ele1) {
                $paragraphStyle = $ele1->getParagraphStyle();
                if ($paragraphStyle) {
                    $html .= '<p style="text-align:' . $paragraphStyle->getAlignment() . ';text-indent:20px;">';
                } else {
                    $html .= '<p>';
                }
                if ($ele1 instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($ele1->getElements() as $ele2) {
                        if ($ele2 instanceof \PhpOffice\PhpWord\Element\Text) {
                            $style = $ele2->getFontStyle();
                            $fontFamily = mb_convert_encoding($style->getName(), 'GBK', 'UTF-8');
                            $fontSize = $style->getSize();
                            $isBold = $style->isBold();
                            $styleString = '';
                            $fontFamily && $styleString .= "font-family:{$fontFamily};";
                            $fontSize && $styleString .= "font-size:{$fontSize}px;";
                            $isBold && $styleString .= "font-weight:bold;";
                            $html .= sprintf('<span style="%s">%s</span>',
                                $styleString,
                                mb_convert_encoding($ele2->getText(), 'GBK', 'UTF-8')
                            );
                        } elseif ($ele2 instanceof \PhpOffice\PhpWord\Element\Image) {
                            $imageSrc = 'images/' . md5($ele2->getSource()) . '.' . $ele2->getImageExtension();
                            $imageData = $ele2->getImageStringData(true);
                            // $imageData = 'data:' . $ele2->getImageType() . ';base64,' . $imageData;
                            file_put_contents($imageSrc, base64_decode($imageData));
                            $html .= '<img src="' . $imageSrc . '" style="width:100%;height:auto">';
                        }
                    }
                }
                $html .= '</p>';
            }
        }
        return mb_convert_encoding($html, 'UTF-8', 'GBK');
    }

    public function cc(){
        $wps = new \COM("WPS.Application");
    }

}