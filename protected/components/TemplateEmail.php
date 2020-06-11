<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TemplateEmail {
    static function getTemplate($nome, $mensagem) {
        $head = TemplateEmail::getHead();
        $body = TemplateEmail::getBody($nome, $mensagem);
        $template = $head 
        . '<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" 
            style="border: 0px; border-color: #000000; background-color: #ffffff;"><center>
            <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
                <tr><td align="center" valign="top" id="bodyCell">
                    <!-- BEGIN TEMPLATE // -->
                    <table border="0" cellpadding="0" cellspacing="0"  id="templateContainer"
                    style="border: 0px solid black; background-color: #ffffff;">
                        <tr><td align="center" valign="top">
                            <!-- BEGIN PREHEADER // -->
                            <!-- <table border="0" cellpadding="0" cellspacing="0"  id="templatePreheader">
                                <tr><td valign="top" class="preheaderContainer" style="padding-top:9px; padding-bottom:9px;">
                                    <table class="mcnImageBlock" cellpadding="0" cellspacing="0" width="100%" border="0">
                                        <tbody class="mcnImageBlockOuter">
                                            <tr>
                                                <td style="padding:9px" class="mcnImageBlockInner" valign="top">
                                                    <table class="mcnImageContentContainer" cellpadding="0" cellspacing="0" width="100%" align="left" border="0">
                                                        <tbody><tr>
                                                            <td class="mcnImageContent"  padding-top: 0; padding-bottom: 0;" valign="top">
                                                                <a href="http://vivasmith.com" title="" class="" target="_blank">
                                                                    <img alt=""
                                                                    src="https://gallery.mailchimp.com/6a1a691b7fdcdd0360d7721c5/images/c82363ca-9d85-41e1-99f8-d62eecba92b6.png"
                                                                    style="max-width:230px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage" width="230"
                                                                    align="left">
                                                                </a>

                                                            </td>
                                                        </tr></tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td></tr>
                            </table> -->
                            <!-- // END PREHEADER -->
                        </td></tr>
                        <tr><td align="center" valign="top">
                            <!-- BEGIN HEADER // -->
                            <table border="0" cellpadding="0" cellspacing="0"  id="templateHeader">
                                <tr>
                                    <td valign="top" class="headerContainer"></td>
                                </tr>
                            </table>
                            <!-- // END HEADER -->
                        </td></tr>
                        <tr><td align="center" valign="top">
                            <!-- BEGIN BODY // -->'
                            . $body .
                            '<!-- // END BODY -->
                        </td></tr>
                    </table>
                    <!-- // END TEMPLATE -->
                </td></tr>
            </table>
        </center></body></html>';
        return $template;
    }
    
    static function getBody($nome, $mensagem){
        return '
        <table border="0" cellpadding="0" cellspacing="0"  id="templateBody"><tr>
            <td valign="top" class="bodyContainer">
                <table class="mcnBoxedTextBlock" cellpadding="0" cellspacing="0" width="100%" border="0">
                    <tbody class="mcnBoxedTextBlockOuter">
                        <tr><td class="mcnBoxedTextBlockInner" valign="top">
                            <table class="mcnBoxedTextContentContainer" cellpadding="0" cellspacing="0"  align="left" border="0">
                                <tbody><tr>
                                    <td style="padding-top:9px; padding-left:18px; padding-bottom:9px; padding-right:18px;">
                                        <table style=" background-color: #FFFFFF;" class="mcnTextContentContainer" cellspacing="0" width="100%" border="0">
                                            <tbody><tr>
                                                <td style="color: #000000;text-align: left;" class="mcnTextContent" valign="top">
                                                    Prezado ' . $nome . ',<br>
                                                    ' . $mensagem . '<br>
                                                    Atenciosamente,
                                                    <br>
                                                    Equipe Viva Inovação
                                                </td>
                                            </tr></tbody>
                                        </table>
                                    </td>
                                </tr></tbody>
                            </table>
                        </td></tr>
                    </tbody>
                </table>
                <table class="mcnBoxedTextBlock" cellpadding="0" cellspacing="0" width="100%" border="0">
                    <tbody class="mcnBoxedTextBlockOuter">
                        <tr>
                            <td class="mcnBoxedTextBlockInner" valign="top">
                                <table class="mcnBoxedTextContentContainer" cellpadding="0" cellspacing="0"  align="left" border="0">
                                    <tbody><tr>
                                        <td style="padding-top:9px; padding-left:18px; padding-bottom:9px; padding-right:18px;">
                                            <table style="border: 1px none #999999;background-color: #FFFFFF;" class="mcnTextContentContainer"  cellspacing="0" width="100%" border="0">
                                                <tbody><tr>
                                                    <td class="mcnTextContent" valign="top">
                                                        <span style="font-size:11px"><span style="color: #FF0000;">
                                                            NOTA: e-mail enviado automaticamente. Favor não responder.
                                                        </span></span>
                                                    </td>
                                                </tr></tbody>
                                            </table>
                                        </td>
                                    </tr></tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr></table>';
    }
    
    static function getHead() {
        $style = TemplateEmail::getStyle();
        return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <!-- NAME: MONOCHROMIC -->
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>*|MC:SUBJECT|*</title>'
                . $style . 
        '</head>';        
    }
    
    static function getStyle() {
        $style = '
        <style type="text/css">
            body,#bodyTable,#bodyCell{
                height:100% !important;
                margin:0;
                padding:0;
                width:100% !important;
            }
            table{
                border-collapse:collapse;
            }
            img,a img{
                border:0;
                outline:none;
                text-decoration:none;
            }
            h1,h2,h3,h4,h5,h6{
                margin:0;
                padding:0;
            }
            p{
                margin:1em 0;
                padding:0;
            }
            a{
                word-wrap:break-word;
            }
            .ReadMsgBody{
                width:100%;
            }
            .ExternalClass{
                width:100%;
            }
            .ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{
                line-height:100%;
            }
            table,td{
                mso-table-lspace:0pt;
                mso-table-rspace:0pt;
            }
            #outlook a{
                padding:0;
            }
            img{
                -ms-interpolation-mode:bicubic;
            }
            body,table,td,p,a,li,blockquote{
                -ms-text-size-adjust:100%;
                -webkit-text-size-adjust:100%;
            }
            #bodyCell{
                padding:20px;
                border-top:0;
            }
            .mcnImage{
                vertical-align:bottom;
            }
            .mcnTextContent img{
                height:auto !important;
            }
            a.mcnButton{
                display:block;
            }
            body,#bodyTable{
                background-color:#ffffff;
            }
            #bodyCell{
                border-top:0;
            }
            /*
            @tab Page
            @section email border
            @tip Set the border for your email.
            */
            #templateContainer{
                border:1px none #000000;
            }
            /*
            @tab Page
            @section heading 1
            @tip Set the styling for all first-level headings in your emails. These should be the largest of your headings.
            @style heading 1
            */
            h1{
                color:#000000 !important;
                display:block;
                font-family:Helvetica;
                font-size:50px;
                font-style:normal;
                font-weight:normal;
                line-height:125%;
                letter-spacing:-1px;
                margin:0;
                text-align:center;
            }
            /*
            @tab Page
            @section heading 2
            @tip Set the styling for all second-level headings in your emails.
            @style heading 2
            */
            h2{
                color:#404040 !important;
                display:block;
                font-family:Helvetica;
                font-size:20px;
                font-style:normal;
                font-weight:normal;
                line-height:125%;
                letter-spacing:-.75px;
                margin:0;
                text-align:left;
            }
            /*
            @tab Page
            @section heading 3
            @tip Set the styling for all third-level headings in your emails.
            @style heading 3
            */
            h3{
                color:#000000 !important;
                display:block;
                font-family:Helvetica;
                font-size:18px;
                font-style:normal;
                font-weight:normal;
                line-height:125%;
                letter-spacing:-.5px;
                margin:0;
                text-align:center;
            }
            /*
            @tab Page
            @section heading 4
            @tip Set the styling for all fourth-level headings in your emails. These should be the smallest of your headings.
            @style heading 4
            */
            h4{
                color:#808080 !important;
                display:block;
                font-family:Helvetica;
                font-size:16px;
                font-style:normal;
                font-weight:normal;
                line-height:125%;
                letter-spacing:normal;
                margin:0;
                text-align:left;
            }
            #templatePreheader{
                background-color:#ffffff;
                border-top:0;
                border-bottom:0;
            }
            .preheaderContainer .mcnTextContent,.preheaderContainer .mcnTextContent p{
                color:#000000;
                font-family:"Times New Roman";
                font-size:12px;
                line-height:125%;
                text-align:left;
            }
            .preheaderContainer .mcnTextContent a{
                color:#000000;
                font-weight:normal;
                text-decoration:underline;
            }
            #templateHeader{
                background-color:#ffffff;
                border-top:1px none #000000;
                border-bottom:2px none #000000;
            }
            .headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{
                color:#000000;
                font-family:"Times New Roman";
                font-size:14px;
                line-height:150%;
                text-align:left;
            }
            .headerContainer .mcnTextContent a{
                color:#000000;
                font-weight:normal;
                text-decoration:underline;
            }
            #templateBody{
                background-color:#FFFFFF;
                border-top:1px none ;
                border-bottom:1px none ;
            }
            .bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{
                color:#000000;
                font-family:"Times New Roman";
                font-size:14px;
                line-height:150%;
                text-align:left;
            }
            .bodyContainer .mcnTextContent a{
                color:#000000;
                font-weight:normal;
                text-decoration:underline;
            }
            #templateColumns{
                background-color:#FFFFFF;
                border-top:0;
                border-bottom:0;
            }
            .leftColumnContainer .mcnTextContent,.leftColumnContainer .mcnTextContent p{
                color:#000000;
                font-family:"Times New Roman";
                font-size:14px;
                line-height:150%;
                text-align:left;
            }
            .leftColumnContainer .mcnTextContent a{
                color:#000000;
                font-weight:normal;
                text-decoration:underline;
            }
            .centerColumnContainer .mcnTextContent,.centerColumnContainer .mcnTextContent p{
                color:#000000;
                font-family:"Times New Roman";
                font-size:14px;
                line-height:150%;
                text-align:left;
            }
            .centerColumnContainer .mcnTextContent a{
                color:#000000;
                font-weight:normal;
                text-decoration:underline;
            }
            .rightColumnContainer .mcnTextContent,.rightColumnContainer .mcnTextContent p{
                color:#000000;
                font-family:"Times New Roman";
                font-size:14px;
                line-height:150%;
                text-align:left;
            }
            .rightColumnContainer .mcnTextContent a{
                color:#000000;
                font-weight:normal;
                text-decoration:underline;
            }
            #templateFooter{
                background-color:#ffffff;
                border-top:1px none ;
                border-bottom:0;
            }
            .footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{
                color:#000000;
                font-family:"Times New Roman";
                font-size:12px;
                line-height:125%;
                text-align:left;
            }
            .footerContainer .mcnTextContent a{
                color:#000000;
                font-weight:normal;
                text-decoration:underline;
            }
            @media only screen and (max-width: 480px){
                body,table,td,p,a,li,blockquote{
                    -webkit-text-size-adjust:none !important;
                }

            }	@media only screen and (max-width: 480px){
                body{
                    width:100% !important;
                    min-width:100% !important;
                }

            }	@media only screen and (max-width: 480px){
                td[id=bodyCell]{
                    padding:10px !important;
                }

            }	@media only screen and (max-width: 480px){
                table[class=mcnTextContentContainer]{
                    width:100% !important;
                }

            }	@media only screen and (max-width: 480px){
                table[class=mcnBoxedTextContentContainer]{
                    width:100% !important;
                }

            }	@media only screen and (max-width: 480px){
                table[class=mcpreview-image-uploader]{
                    width:100% !important;
                    display:none !important;
                }

            }	@media only screen and (max-width: 480px){
                img[class=mcnImage]{
                    width:100% !important;
                }

            }	@media only screen and (max-width: 480px){
                table[class=mcnImageGroupContentContainer]{
                    width:100% !important;
                }

            }	@media only screen and (max-width: 480px){
                td[class=mcnImageGroupContent]{
                    padding:9px !important;
                }

            }	@media only screen and (max-width: 480px){
                td[class=mcnImageGroupBlockInner]{
                    padding-bottom:0 !important;
                    padding-top:0 !important;
                }

            }	@media only screen and (max-width: 480px){
                tbody[class=mcnImageGroupBlockOuter]{
                    padding-bottom:9px !important;
                    padding-top:9px !important;
                }

            }	@media only screen and (max-width: 480px){
                table[class=mcnCaptionTopContent],table[class=mcnCaptionBottomContent]{
                    width:100% !important;
                }

            }	@media only screen and (max-width: 480px){
                table[class=mcnCaptionLeftTextContentContainer],table[class=mcnCaptionRightTextContentContainer],table[class=mcnCaptionLeftImageContentContainer],table[class=mcnCaptionRightImageContentContainer],table[class=mcnImageCardLeftTextContentContainer],table[class=mcnImageCardRightTextContentContainer]{
                    width:100% !important;
                }

            }	@media only screen and (max-width: 480px){
                td[class=mcnImageCardLeftImageContent],td[class=mcnImageCardRightImageContent]{
                    /*padding-right:18px !important;
                    padding-left:18px !important;*/
                    padding-bottom:0 !important;
                }

            }	@media only screen and (max-width: 480px){
                td[class=mcnImageCardBottomImageContent]{
                    padding-bottom:9px !important;
                }

            }	@media only screen and (max-width: 480px){
                td[class=mcnImageCardTopImageContent]{
                    padding-top:18px !important;
                }

            }	@media only screen and (max-width: 480px){
                table[class=mcnCaptionLeftContentOuter] td[class=mcnTextContent],table[class=mcnCaptionRightContentOuter] td[class=mcnTextContent]{
                    padding-top:9px !important;
                }

            }	@media only screen and (max-width: 480px){
                td[class=mcnCaptionBlockInner] table[class=mcnCaptionTopContent]:last-child td[class=mcnTextContent]{
                    padding-top:18px !important;
                }

            }	@media only screen and (max-width: 480px){
                td[class=mcnBoxedTextContentColumn]{
                    /*padding-left:18px !important;
                    padding-right:18px !important;*/
                }

            }	@media only screen and (max-width: 480px){
                td[class=columnsContainer]{
                    display:block !important;
                    max-width:600px !important;
                    width:100% !important;
                }

            }	@media only screen and (max-width: 480px){
                td[class=mcnTextContent]{
                    /*padding-right:18px !important;
                    padding-left:18px !important;*/
                }

            }	@media only screen and (max-width: 480px){
                table[id=templateContainer],table[id=templatePreheader],table[id=templateHeader],table[id=templateColumns],table[class=templateColumn],table[id=templateBody],table[id=templateFooter]{
                    /*max-width:600px !important;*/
                    width:100% !important;
                }

            }	@media only screen and (max-width: 480px){
                /*
                @tab Mobile Styles
                @section heading 1
                @tip Make the first-level headings larger in size for better readability on small screens.
                */
                h1{
                    font-size:24px !important;
                    line-height:125% !important;
                }

            }	@media only screen and (max-width: 480px){
                /*
                @tab Mobile Styles
                @section heading 2
                @tip Make the second-level headings larger in size for better readability on small screens.
                */
                h2{
                    font-size:20px !important;
                    line-height:125% !important;
                }

            }	@media only screen and (max-width: 480px){
                /*
                @tab Mobile Styles
                @section heading 3
                @tip Make the third-level headings larger in size for better readability on small screens.
                */
                h3{
                    font-size:18px !important;
                    line-height:125% !important;
                }

            }	@media only screen and (max-width: 480px){
                /*
                @tab Mobile Styles
                @section heading 4
                @tip Make the fourth-level headings larger in size for better readability on small screens.
                */
                h4{
                    font-size:16px !important;
                    line-height:125% !important;
                }

            }	@media only screen and (max-width: 480px){
                /*
                @tab Mobile Styles
                @section Boxed Text
                @tip Make the boxed text larger in size for better readability on small screens. We recommend a font size of at least 16px.
                */
                table[class=mcnBoxedTextContentContainer] td[class=mcnTextContent],td[class=mcnBoxedTextContentContainer] td[class=mcnTextContent] p{
                    font-size:18px !important;
                    line-height:125% !important;
                }

            }	@media only screen and (max-width: 480px){
                table[id=templatePreheader]{
                    display:block !important;
                }

            }	@media only screen and (max-width: 480px){
                /*
                @tab Mobile Styles
                @section Preheader Text
                @tip Make the preheader text larger in size for better readability on small screens.
                */
                td[class=preheaderContainer] td[class=mcnTextContent],td[class=preheaderContainer] td[class=mcnTextContent] p{
                    font-size:14px !important;
                    line-height:115% !important;
                }

            }	@media only screen and (max-width: 480px){
                /*
                @tab Mobile Styles
                @section Header Text
                @tip Make the header text larger in size for better readability on small screens.
                */
                td[class=headerContainer] td[class=mcnTextContent],td[class=headerContainer] td[class=mcnTextContent] p{
                    font-size:18px !important;
                    line-height:125% !important;
                }

            }	@media only screen and (max-width: 480px){
                /*
                @tab Mobile Styles
                @section Body Text
                @tip Make the body text larger in size for better readability on small screens. We recommend a font size of at least 16px.
                */
                td[class=bodyContainer] td[class=mcnTextContent],td[class=bodyContainer] td[class=mcnTextContent] p{
                    font-size:18px !important;
                    line-height:125% !important;
                }

            }	@media only screen and (max-width: 480px){
                /*
                @tab Mobile Styles
                @section Left Column Text
                @tip Make the left column text larger in size for better readability on small screens. We recommend a font size of at least 16px.
                */
                td[class=leftColumnContainer] td[class=mcnTextContent],td[class=leftColumnContainer] td[class=mcnTextContent] p{
                    font-size:18px !important;
                    line-height:125% !important;
                }

            }	@media only screen and (max-width: 480px){
                /*
                @tab Mobile Styles
                @section Center Column Text
                @tip Make the center column text larger in size for better readability on small screens. We recommend a font size of at least 16px.
                */
                td[class=centerColumnContainer] td[class=mcnTextContent],td[class=centerColumnContainer] td[class=mcnTextContent] p{
                    font-size:18px !important;
                    line-height:125% !important;
                }

            }	@media only screen and (max-width: 480px){
                /*
                @tab Mobile Styles
                @section Right Column Text
                @tip Make the right column text larger in size for better readability on small screens. We recommend a font size of at least 16px.
                */
                td[class=rightColumnContainer] td[class=mcnTextContent],td[class=rightColumnContainer] td[class=mcnTextContent] p{
                    font-size:18px !important;
                    line-height:125% !important;
                }

            }	@media only screen and (max-width: 480px){
                /*
                @tab Mobile Styles
                @section footer text
                @tip Make the body content text larger in size for better readability on small screens.
                */
                td[class=footerContainer] td[class=mcnTextContent],td[class=footerContainer] td[class=mcnTextContent] p{
                    font-size:14px !important;
                    line-height:115% !important;
                }

            }	@media only screen and (max-width: 480px){
                td[class=footerContainer] a[class=utilityLink]{
                    display:block !important;
                }

            }
        </style>';
        return $style;
    }
}