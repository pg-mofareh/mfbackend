<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use Mail;
use App\Mail\SendMail;
trait MailTrait
{

    public function SendMailTrait($subjet,$title,$body,$to,$target)
    {
        $mailData =[
            'target'=>$target,
            'subject'=>$subjet,
            'title'=>$title,
            'body'=>$body
        ];
        if(Mail::to($to)->send(new SendMail($mailData))){
            return true;
        }else{
            return false;
        } 
    }


    ##### use bottom method to send mails 
    /*
        if($this->SendMailTrait('subject','title','body','to')){
            # Mail sent
        }
    */

}