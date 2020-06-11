/*
 * File:        jquery.dataTables.min.js
 * Version:     1.8.1
 * Author:      Allan Jardine (www.sprymedia.co.uk)
 * Info:        www.datatables.net
 * 
 * Copyright 2008-2011 Allan Jardine, all rights reserved.
 *
 * This source file is free software, under either the GPL v2 license or a
 * BSD style license, as supplied with this software.
 * 
 * This source file is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY 
 * or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.
 */

(function(i,wa,p){
    i.fn.dataTableSettings=[];
    var D=i.fn.dataTableSettings;
    i.fn.dataTableExt={
    };
    
    var o=i.fn.dataTableExt;
    o.sVersion="1.8.1";
    o.sErrMode="alert";
    o.iApiIndex=0;
    o.oApi={
    };
    
    o.afnFiltering=[];
    o.aoFeatures=[];
    o.ofnSearch={
    };
    
    o.afnSortData=[];
    o.oStdClasses={
        sPagePrevEnabled:"paginate_enabled_previous",
        sPagePrevDisabled:"paginate_disabled_previous",
        sPageNextEnabled:"paginate_enabled_next",
        sPageNextDisabled:"paginate_disabled_next",
        sPageJUINext:"",
        sPageJUIPrev:"",
        sPageButton:"paginate_button",
        sPageButtonActive:"paginate_active",
        sPageButtonStaticDisabled:"paginate_button paginate_button_disabled",
        sPageFirst:"first",
        sPagePrevious:"previous",
        sPageNext:"next",
        sPageLast:"last",
        sStripOdd:"odd",
        sStripEven:"even",
        sRowEmpty:"dataTables_empty",
        sWrapper:"dataTables_wrapper",
        sFilter:"dataTables_filter",
        sInfo:"dataTables_info",
        sPaging:"dataTables_paginate paging_",
        sLength:"dataTables_length",
        sProcessing:"dataTables_processing",
        sSortAsc:"sorting_asc",
        sSortDesc:"sorting_desc",
        sSortable:"sorting",
        sSortableAsc:"sorting_asc_disabled",
        sSortableDesc:"sorting_desc_disabled",
        sSortableNone:"sorting_disabled",
        sSortColumn:"sorting_",
        sSortJUIAsc:"",
        sSortJUIDesc:"",
        sSortJUI:"",
        sSortJUIAscAllowed:"",
        sSortJUIDescAllowed:"",
        sSortJUIWrapper:"",
        sSortIcon:"",
        sScrollWrapper:"dataTables_scroll",
        sScrollHead:"dataTables_scrollHead",
        sScrollHeadInner:"dataTables_scrollHeadInner",
        sScrollBody:"dataTables_scrollBody",
        sScrollFoot:"dataTables_scrollFoot",
        sScrollFootInner:"dataTables_scrollFootInner",
        sFooterTH:""
    };
    
    o.oJUIClasses={
        sPagePrevEnabled:"fg-button ui-button ui-state-default ui-corner-left",
        sPagePrevDisabled:"fg-button ui-button ui-state-default ui-corner-left ui-state-disabled",
        sPageNextEnabled:"fg-button ui-button ui-state-default ui-corner-right",
        sPageNextDisabled:"fg-button ui-button ui-state-default ui-corner-right ui-state-disabled",
        sPageJUINext:"ui-icon ui-icon-circle-arrow-e",
        sPageJUIPrev:"ui-icon ui-icon-circle-arrow-w",
        sPageButton:"fg-button ui-button ui-state-default",
        sPageButtonActive:"fg-button ui-button ui-state-default ui-state-disabled",
        sPageButtonStaticDisabled:"fg-button ui-button ui-state-default ui-state-disabled",
        sPageFirst:"first ui-corner-tl ui-corner-bl",
        sPagePrevious:"previous",
        sPageNext:"next",
        sPageLast:"last ui-corner-tr ui-corner-br",
        sStripOdd:"odd",
        sStripEven:"even",
        sRowEmpty:"dataTables_empty",
        sWrapper:"dataTables_wrapper",
        sFilter:"dataTables_filter",
        sInfo:"dataTables_info",
        sPaging:"dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_",
        sLength:"dataTables_length",
        sProcessing:"dataTables_processing",
        sSortAsc:"ui-state-default",
        sSortDesc:"ui-state-default",
        sSortable:"ui-state-default",
        sSortableAsc:"ui-state-default",
        sSortableDesc:"ui-state-default",
        sSortableNone:"ui-state-default",
        sSortColumn:"sorting_",
        sSortJUIAsc:"css_right ui-icon ui-icon-triangle-1-n",
        sSortJUIDesc:"css_right ui-icon ui-icon-triangle-1-s",
        sSortJUI:"css_right ui-icon ui-icon-carat-2-n-s",
        sSortJUIAscAllowed:"css_right ui-icon ui-icon-carat-1-n",
        sSortJUIDescAllowed:"css_right ui-icon ui-icon-carat-1-s",
        sSortJUIWrapper:"DataTables_sort_wrapper",
        sSortIcon:"DataTables_sort_icon",
        sScrollWrapper:"dataTables_scroll",
        sScrollHead:"dataTables_scrollHead ui-state-default",
        sScrollHeadInner:"dataTables_scrollHeadInner",
        sScrollBody:"dataTables_scrollBody",
        sScrollFoot:"dataTables_scrollFoot ui-state-default",
        sScrollFootInner:"dataTables_scrollFootInner",
        sFooterTH:"ui-state-default"
    };
    
    o.oPagination={
        two_button:{
            fnInit:function(g,l,r){
                var s,w,y;
                if(g.bJUI){
                    s=p.createElement("a");
                    w=p.createElement("a");
                    y=p.createElement("span");
                    y.className=g.oClasses.sPageJUINext;
                    w.appendChild(y);
                    y=p.createElement("span");
                    y.className=g.oClasses.sPageJUIPrev;
                    s.appendChild(y)
                    }else{
                    s=p.createElement("div");
                    w=p.createElement("div")
                    }
                    s.className=g.oClasses.sPagePrevDisabled;
                w.className=g.oClasses.sPageNextDisabled;
                s.title=g.oLanguage.oPaginate.sPrevious;
                w.title=g.oLanguage.oPaginate.sNext;
                l.appendChild(s);
                l.appendChild(w);
                i(s).bind("click.DT",function(){
                    g.oApi._fnPageChange(g,"previous")&&r(g)
                    });
                i(w).bind("click.DT",function(){
                    g.oApi._fnPageChange(g,"next")&&r(g)
                    });
                i(s).bind("selectstart.DT",function(){
                    return false
                    });
                i(w).bind("selectstart.DT",function(){
                    return false
                    });
                if(g.sTableId!==""&&typeof g.aanFeatures.p=="undefined"){
                    l.setAttribute("id",g.sTableId+"_paginate");
                    s.setAttribute("id",g.sTableId+"_previous");
                    w.setAttribute("id",g.sTableId+"_next")
                    }
                },
        fnUpdate:function(g){
            if(g.aanFeatures.p)for(var l=g.aanFeatures.p,r=0,s=l.length;r<s;r++)if(l[r].childNodes.length!==0){
                l[r].childNodes[0].className=g._iDisplayStart===0?g.oClasses.sPagePrevDisabled:g.oClasses.sPagePrevEnabled;
                l[r].childNodes[1].className=g.fnDisplayEnd()==g.fnRecordsDisplay()?g.oClasses.sPageNextDisabled:
                g.oClasses.sPageNextEnabled
                }
            }
            },
iFullNumbersShowPages:5,
full_numbers:{
    fnInit:function(g,l,r){
        var s=p.createElement("span"),w=p.createElement("span"),y=p.createElement("span"),G=p.createElement("span"),x=p.createElement("span");
        s.innerHTML=g.oLanguage.oPaginate.sFirst;
        w.innerHTML=g.oLanguage.oPaginate.sPrevious;
        G.innerHTML=g.oLanguage.oPaginate.sNext;
        x.innerHTML=g.oLanguage.oPaginate.sLast;
        var v=g.oClasses;
        s.className=v.sPageButton+" "+v.sPageFirst;
        w.className=v.sPageButton+" "+v.sPagePrevious;
        G.className=
        v.sPageButton+" "+v.sPageNext;
        x.className=v.sPageButton+" "+v.sPageLast;
        l.appendChild(s);
        l.appendChild(w);
        l.appendChild(y);
        l.appendChild(G);
        l.appendChild(x);
        i(s).bind("click.DT",function(){
            g.oApi._fnPageChange(g,"first")&&r(g)
            });
        i(w).bind("click.DT",function(){
            g.oApi._fnPageChange(g,"previous")&&r(g)
            });
        i(G).bind("click.DT",function(){
            g.oApi._fnPageChange(g,"next")&&r(g)
            });
        i(x).bind("click.DT",function(){
            g.oApi._fnPageChange(g,"last")&&r(g)
            });
        i("span",l).bind("mousedown.DT",function(){
            return false
            }).bind("selectstart.DT",
            function(){
                return false
                });
        if(g.sTableId!==""&&typeof g.aanFeatures.p=="undefined"){
            l.setAttribute("id",g.sTableId+"_paginate");
            s.setAttribute("id",g.sTableId+"_first");
            w.setAttribute("id",g.sTableId+"_previous");
            G.setAttribute("id",g.sTableId+"_next");
            x.setAttribute("id",g.sTableId+"_last")
            }
        },
fnUpdate:function(g,l){
    if(g.aanFeatures.p){
        var r=o.oPagination.iFullNumbersShowPages,s=Math.floor(r/2),w=Math.ceil(g.fnRecordsDisplay()/g._iDisplayLength),y=Math.ceil(g._iDisplayStart/g._iDisplayLength)+1,G=
        "",x,v=g.oClasses;
        if(w<r){
            s=1;
            x=w
            }else if(y<=s){
            s=1;
            x=r
            }else if(y>=w-s){
            s=w-r+1;
            x=w
            }else{
            s=y-Math.ceil(r/2)+1;
            x=s+r-1
            }
            for(r=s;r<=x;r++)G+=y!=r?'<span class="'+v.sPageButton+'">'+r+"</span>":'<span class="'+v.sPageButtonActive+'">'+r+"</span>";
        x=g.aanFeatures.p;
        var z,Y=function(L){
            g._iDisplayStart=(this.innerHTML*1-1)*g._iDisplayLength;
            l(g);
            L.preventDefault()
            },V=function(){
            return false
            };
            
        r=0;
        for(s=x.length;r<s;r++)if(x[r].childNodes.length!==0){
            z=i("span:eq(2)",x[r]);
            z.html(G);
            i("span",z).bind("click.DT",
                Y).bind("mousedown.DT",V).bind("selectstart.DT",V);
            z=x[r].getElementsByTagName("span");
            z=[z[0],z[1],z[z.length-2],z[z.length-1]];
            i(z).removeClass(v.sPageButton+" "+v.sPageButtonActive+" "+v.sPageButtonStaticDisabled);
            if(y==1){
                z[0].className+=" "+v.sPageButtonStaticDisabled;
                z[1].className+=" "+v.sPageButtonStaticDisabled
                }else{
                z[0].className+=" "+v.sPageButton;
                z[1].className+=" "+v.sPageButton
                }
                if(w===0||y==w||g._iDisplayLength==-1){
                z[2].className+=" "+v.sPageButtonStaticDisabled;
                z[3].className+=" "+
                v.sPageButtonStaticDisabled
                }else{
                z[2].className+=" "+v.sPageButton;
                z[3].className+=" "+v.sPageButton
                }
            }
        }
}
}
};

o.oSort={
    "string-asc":function(g,l){
        if(typeof g!="string")g="";
        if(typeof l!="string")l="";
        g=g.toLowerCase();
        l=l.toLowerCase();
        return g<l?-1:g>l?1:0
        },
    "string-desc":function(g,l){
        if(typeof g!="string")g="";
        if(typeof l!="string")l="";
        g=g.toLowerCase();
        l=l.toLowerCase();
        return g<l?1:g>l?-1:0
        },
    "html-asc":function(g,l){
        g=g.replace(/<.*?>/g,"").toLowerCase();
        l=l.replace(/<.*?>/g,"").toLowerCase();
        return g<
        l?-1:g>l?1:0
        },
    "html-desc":function(g,l){
        g=g.replace(/<.*?>/g,"").toLowerCase();
        l=l.replace(/<.*?>/g,"").toLowerCase();
        return g<l?1:g>l?-1:0
        },
    "date-asc":function(g,l){
        g=Date.parse(g);
        l=Date.parse(l);
        if(isNaN(g)||g==="")g=Date.parse("01/01/1970 00:00:00");
        if(isNaN(l)||l==="")l=Date.parse("01/01/1970 00:00:00");
        return g-l
        },
    "date-desc":function(g,l){
        g=Date.parse(g);
        l=Date.parse(l);
        if(isNaN(g)||g==="")g=Date.parse("01/01/1970 00:00:00");
        if(isNaN(l)||l==="")l=Date.parse("01/01/1970 00:00:00");
        return l-
        g
        },
    "numeric-asc":function(g,l){
        return(g=="-"||g===""?0:g*1)-(l=="-"||l===""?0:l*1)
        },
    "numeric-desc":function(g,l){
        return(l=="-"||l===""?0:l*1)-(g=="-"||g===""?0:g*1)
        }
    };

o.aTypes=[function(g){
    if(typeof g=="number")return"numeric";
    else if(typeof g!="string")return null;
    var l,r=false;
    l=g.charAt(0);
    if("0123456789-".indexOf(l)==-1)return null;
    for(var s=1;s<g.length;s++){
        l=g.charAt(s);
        if("0123456789.".indexOf(l)==-1)return null;
        if(l=="."){
            if(r)return null;
            r=true
            }
        }
    return"numeric"
},function(g){
    var l=Date.parse(g);
    if(l!==null&&!isNaN(l)||typeof g=="string"&&g.length===0)return"date";
    return null
    },function(g){
    if(typeof g=="string"&&g.indexOf("<")!=-1&&g.indexOf(">")!=-1)return"html";
    return null
    }];
o.fnVersionCheck=function(g){
    var l=function(x,v){
        for(;x.length<v;)x+="0";
        return x
        },r=o.sVersion.split(".");
    g=g.split(".");
    for(var s="",w="",y=0,G=g.length;y<G;y++){
        s+=l(r[y],3);
        w+=l(g[y],3)
        }
        return parseInt(s,10)>=parseInt(w,10)
    };
    
o._oExternConfig={
    iNextUnique:0
};

i.fn.dataTable=function(g){
    function l(){
        this.fnRecordsTotal=
        function(){
            return this.oFeatures.bServerSide?parseInt(this._iRecordsTotal,10):this.aiDisplayMaster.length
            };
            
        this.fnRecordsDisplay=function(){
            return this.oFeatures.bServerSide?parseInt(this._iRecordsDisplay,10):this.aiDisplay.length
            };
            
        this.fnDisplayEnd=function(){
            return this.oFeatures.bServerSide?this.oFeatures.bPaginate===false||this._iDisplayLength==-1?this._iDisplayStart+this.aiDisplay.length:Math.min(this._iDisplayStart+this._iDisplayLength,this._iRecordsDisplay):this._iDisplayEnd
            };
            
        this.sInstance=
        this.oInstance=null;
        this.oFeatures={
            bPaginate:true,
            bLengthChange:true,
            bFilter:true,
            bSort:true,
            bInfo:true,
            bAutoWidth:true,
            bProcessing:false,
            bSortClasses:true,
            bStateSave:false,
            bServerSide:false,
            bDeferRender:false
        };
        
        this.oScroll={
            sX:"",
            sXInner:"",
            sY:"",
            bCollapse:false,
            bInfinite:false,
            iLoadGap:100,
            iBarWidth:0,
            bAutoCss:true
        };
        
        this.aanFeatures=[];
        this.oLanguage={
            sProcessing:"Processing...",
            sLengthMenu:"Show _MENU_ entries",
            sZeroRecords: "Nenhum resultado encontrado",
            sEmptyTable:"No data available in table",
            sLoadingRecords:"Loading...",
            sInfo:"Showing _START_ to _END_ of _TOTAL_ entries",
            sInfoEmpty:"Showing 0 to 0 of 0 entries",
            sInfoFiltered:"(filtered from _MAX_ total entries)",
            sInfoPostFix:"",
            sSearch:"Search:",
            sUrl:"",
            oPaginate:{
                sFirst:"First",
                sPrevious:"Previous",
                sNext:"Next",
                sLast:"Last"
            },
            fnInfoCallback:null
        };
        
        this.aoData=[];
        this.aiDisplay=[];
        this.aiDisplayMaster=[];
        this.aoColumns=[];
        this.aoHeader=[];
        this.aoFooter=[];
        this.iNextId=0;
        this.asDataSearch=[];
        this.oPreviousSearch={
            sSearch:"",
            bRegex:false,
            bSmart:true
        };
        
        this.aoPreSearchCols=[];
        this.aaSorting=[[0,"asc",0]];
        this.aaSortingFixed=null;
        this.asStripClasses=[];
        this.asDestoryStrips=[];
        this.sDestroyWidth=0;
        this.fnFooterCallback=this.fnHeaderCallback=this.fnRowCallback=null;
        this.aoDrawCallback=[];
        this.fnInitComplete=this.fnPreDrawCallback=null;
        this.sTableId="";
        this.nTableWrapper=this.nTBody=this.nTFoot=this.nTHead=this.nTable=null;
        this.bInitialised=this.bDeferLoading=false;
        this.aoOpenRows=[];
        this.sDom='<"top"lf<"clear">>rt<"block-actions"ip>';
        this.sPaginationType="full_numbers";
        this.iCookieDuration=7200;
        this.sCookiePrefix="SpryMedia_DataTables_";
        this.fnCookieCallback=null;
        this.aoStateSave=[];
        this.aoStateLoad=[];
        this.sAjaxSource=this.oLoadedState=null;
        this.sAjaxDataProp="aaData";
        this.bAjaxDataGet=true;
        this.jqXHR=null;
        this.fnServerData=function(a,b,c,d){
            d.jqXHR=i.ajax({
                url:a,
                data:b,
                success:c,
                dataType:"json",
                cache:false,
                error:function(f,e){
                    e=="parsererror"&&alert("DataTables warning: JSON data from server could not be parsed. This is caused by a JSON formatting error.")
                    }
                })
        };
    this.fnFormatNumber=function(a){
        if(a<1E3)return a;
        else{
            var b=a+"";
            a=b.split("");
            var c="";
            b=b.length;
            for(var d=0;d<b;d++){
                if(d%3===0&&d!==0)c=","+c;
                c=a[b-d-1]+c
                }
            }
            return c
    };
    
this.aLengthMenu=[10,25,50,100];
this.bDrawing=this.iDraw=0;
this.iDrawError=-1;
this._iDisplayLength=10;
this._iDisplayStart=0;
this._iDisplayEnd=10;
this._iRecordsDisplay=this._iRecordsTotal=0;
this.bJUI=false;
this.oClasses=o.oStdClasses;
this.bSortCellsTop=this.bSorted=this.bFiltered=false;
this.oInit=null
}
function r(a){
    return function(){
        var b=
        [A(this[o.iApiIndex])].concat(Array.prototype.slice.call(arguments));
        return o.oApi[a].apply(this,b)
        }
    }
function s(a){
    var b,c,d=a.iInitDisplayStart;
    if(a.bInitialised===false)setTimeout(function(){
        s(a)
        },200);
    else{
        xa(a);
        V(a);
        L(a,a.aoHeader);
        a.nTFoot&&L(a,a.aoFooter);
        K(a,true);
        a.oFeatures.bAutoWidth&&ea(a);
        b=0;
        for(c=a.aoColumns.length;b<c;b++)if(a.aoColumns[b].sWidth!==null)a.aoColumns[b].nTh.style.width=u(a.aoColumns[b].sWidth);if(a.oFeatures.bSort)R(a);
        else if(a.oFeatures.bFilter)M(a,a.oPreviousSearch);
        else{
            a.aiDisplay=a.aiDisplayMaster.slice();
            E(a);
            C(a)
            }
            if(a.sAjaxSource!==null&&!a.oFeatures.bServerSide)a.fnServerData.call(a.oInstance,a.sAjaxSource,[],function(f){
            var e=f;
            if(a.sAjaxDataProp!=="")e=Z(a.sAjaxDataProp)(f);
            for(b=0;b<e.length;b++)v(a,e[b]);
            a.iInitDisplayStart=d;
            if(a.oFeatures.bSort)R(a);
            else{
                a.aiDisplay=a.aiDisplayMaster.slice();
                E(a);
                C(a)
                }
                K(a,false);
            w(a,f)
            },a);
        else if(!a.oFeatures.bServerSide){
            K(a,false);
            w(a)
            }
        }
}
function w(a,b){
    a._bInitComplete=true;
    if(typeof a.fnInitComplete=="function")typeof b!=
        "undefined"?a.fnInitComplete.call(a.oInstance,a,b):a.fnInitComplete.call(a.oInstance,a)
        }
        function y(a,b,c){
    n(a.oLanguage,b,"sProcessing");
    n(a.oLanguage,b,"sLengthMenu");
    n(a.oLanguage,b,"sEmptyTable");
    n(a.oLanguage,b,"sLoadingRecords");
    n(a.oLanguage,b,"sZeroRecords");
    n(a.oLanguage,b,"sInfo");
    n(a.oLanguage,b,"sInfoEmpty");
    n(a.oLanguage,b,"sInfoFiltered");
    n(a.oLanguage,b,"sInfoPostFix");
    n(a.oLanguage,b,"sSearch");
    if(typeof b.oPaginate!="undefined"){
        n(a.oLanguage.oPaginate,b.oPaginate,"sFirst");
        n(a.oLanguage.oPaginate,
            b.oPaginate,"sPrevious");
        n(a.oLanguage.oPaginate,b.oPaginate,"sNext");
        n(a.oLanguage.oPaginate,b.oPaginate,"sLast")
        }
        typeof b.sEmptyTable=="undefined"&&typeof b.sZeroRecords!="undefined"&&n(a.oLanguage,b,"sZeroRecords","sEmptyTable");
    typeof b.sLoadingRecords=="undefined"&&typeof b.sZeroRecords!="undefined"&&n(a.oLanguage,b,"sZeroRecords","sLoadingRecords");
    c&&s(a)
    }
    function G(a,b){
    var c=a.aoColumns.length;
    b={
        sType:null,
        _bAutoType:true,
        bVisible:true,
        bSearchable:true,
        bSortable:true,
        asSorting:["asc","desc"],
        sSortingClass:a.oClasses.sSortable,
        sSortingClassJUI:a.oClasses.sSortJUI,
        sTitle:b?b.innerHTML:"",
        sName:"",
        sWidth:null,
        sWidthOrig:null,
        sClass:null,
        fnRender:null,
        bUseRendered:true,
        iDataSort:c,
        mDataProp:c,
        fnGetData:null,
        fnSetData:null,
        sSortDataType:"std",
        sDefaultContent:null,
        sContentPadding:"",
        nTh:b?b:p.createElement("th"),
        nTf:null
    };
    
    a.aoColumns.push(b);
    if(typeof a.aoPreSearchCols[c]=="undefined"||a.aoPreSearchCols[c]===null)a.aoPreSearchCols[c]={
        sSearch:"",
        bRegex:false,
        bSmart:true
    };
    else{
        if(typeof a.aoPreSearchCols[c].bRegex==
            "undefined")a.aoPreSearchCols[c].bRegex=true;
        if(typeof a.aoPreSearchCols[c].bSmart=="undefined")a.aoPreSearchCols[c].bSmart=true
            }
            x(a,c,null)
    }
    function x(a,b,c){
    b=a.aoColumns[b];
    if(typeof c!="undefined"&&c!==null){
        if(typeof c.sType!="undefined"){
            b.sType=c.sType;
            b._bAutoType=false
            }
            n(b,c,"bVisible");
        n(b,c,"bSearchable");
        n(b,c,"bSortable");
        n(b,c,"sTitle");
        n(b,c,"sName");
        n(b,c,"sWidth");
        n(b,c,"sWidth","sWidthOrig");
        n(b,c,"sClass");
        n(b,c,"fnRender");
        n(b,c,"bUseRendered");
        n(b,c,"iDataSort");
        n(b,c,"mDataProp");
        n(b,c,"asSorting");
        n(b,c,"sSortDataType");
        n(b,c,"sDefaultContent");
        n(b,c,"sContentPadding")
        }
        b.fnGetData=Z(b.mDataProp);
    b.fnSetData=ya(b.mDataProp);
    if(!a.oFeatures.bSort)b.bSortable=false;
    if(!b.bSortable||i.inArray("asc",b.asSorting)==-1&&i.inArray("desc",b.asSorting)==-1){
        b.sSortingClass=a.oClasses.sSortableNone;
        b.sSortingClassJUI=""
        }else if(b.bSortable||i.inArray("asc",b.asSorting)==-1&&i.inArray("desc",b.asSorting)==-1){
        b.sSortingClass=a.oClasses.sSortable;
        b.sSortingClassJUI=a.oClasses.sSortJUI
        }else if(i.inArray("asc",
        b.asSorting)!=-1&&i.inArray("desc",b.asSorting)==-1){
        b.sSortingClass=a.oClasses.sSortableAsc;
        b.sSortingClassJUI=a.oClasses.sSortJUIAscAllowed
        }else if(i.inArray("asc",b.asSorting)==-1&&i.inArray("desc",b.asSorting)!=-1){
        b.sSortingClass=a.oClasses.sSortableDesc;
        b.sSortingClassJUI=a.oClasses.sSortJUIDescAllowed
        }
    }
function v(a,b){
    var c;
    c=typeof b.length=="number"?b.slice():i.extend(true,{
        },b);
    b=a.aoData.length;
    var d={
        nTr:null,
        _iId:a.iNextId++,
        _aData:c,
        _anHidden:[],
        _sRowStripe:""
    };
    
    a.aoData.push(d);
    for(var f,
        e=0,h=a.aoColumns.length;e<h;e++){
        c=a.aoColumns[e];
        typeof c.fnRender=="function"&&c.bUseRendered&&c.mDataProp!==null&&N(a,b,e,c.fnRender({
            iDataRow:b,
            iDataColumn:e,
            aData:d._aData,
            oSettings:a
        }));
        if(c._bAutoType&&c.sType!="string"){
            f=H(a,b,e,"type");
            if(f!==null&&f!==""){
                f=fa(f);
                if(c.sType===null)c.sType=f;
                else if(c.sType!=f)c.sType="string"
                    }
                }
    }
    a.aiDisplayMaster.push(b);
a.oFeatures.bDeferRender||z(a,b);
return b
}
function z(a,b){
    var c=a.aoData[b],d;
    if(c.nTr===null){
        c.nTr=p.createElement("tr");
        typeof c._aData.DT_RowId!=
        "undefined"&&c.nTr.setAttribute("id",c._aData.DT_RowId);
        typeof c._aData.DT_RowClass!="undefined"&&i(c.nTr).addClass(c._aData.DT_RowClass);
        for(var f=0,e=a.aoColumns.length;f<e;f++){
            var h=a.aoColumns[f];
            d=p.createElement("td");
            d.innerHTML=typeof h.fnRender=="function"&&(!h.bUseRendered||h.mDataProp===null)?h.fnRender({
                iDataRow:b,
                iDataColumn:f,
                aData:c._aData,
                oSettings:a
            }):H(a,b,f,"display");
            if(h.sClass!==null)d.className=h.sClass;
            if(h.bVisible){
                c.nTr.appendChild(d);
                c._anHidden[f]=null
                }else c._anHidden[f]=
                d
                }
            }
    }
function Y(a){
    var b,c,d,f,e,h,j,k,m;
    if(a.bDeferLoading||a.sAjaxSource===null){
        j=a.nTBody.childNodes;
        b=0;
        for(c=j.length;b<c;b++)if(j[b].nodeName.toUpperCase()=="TR"){
            k=a.aoData.length;
            a.aoData.push({
                nTr:j[b],
                _iId:a.iNextId++,
                _aData:[],
                _anHidden:[],
                _sRowStripe:""
            });
            a.aiDisplayMaster.push(k);
            h=j[b].childNodes;
            d=e=0;
            for(f=h.length;d<f;d++){
                m=h[d].nodeName.toUpperCase();
                if(m=="TD"||m=="TH"){
                    N(a,k,e,i.trim(h[d].innerHTML));
                    e++
                }
            }
            }
        }
j=$(a);
h=[];
b=0;
for(c=j.length;b<c;b++){
    d=0;
    for(f=j[b].childNodes.length;d<
        f;d++){
        e=j[b].childNodes[d];
        m=e.nodeName.toUpperCase();
        if(m=="TD"||m=="TH")h.push(e)
            }
        }
    h.length!=j.length*a.aoColumns.length&&J(a,1,"Unexpected number of TD elements. Expected "+j.length*a.aoColumns.length+" and got "+h.length+". DataTables does not support rowspan / colspan in the table body, and there must be one cell for each row/column combination.");
d=0;
for(f=a.aoColumns.length;d<f;d++){
    if(a.aoColumns[d].sTitle===null)a.aoColumns[d].sTitle=a.aoColumns[d].nTh.innerHTML;
    j=a.aoColumns[d]._bAutoType;
    m=typeof a.aoColumns[d].fnRender=="function";
    e=a.aoColumns[d].sClass!==null;
    k=a.aoColumns[d].bVisible;
    var t,q;
    if(j||m||e||!k){
        b=0;
        for(c=a.aoData.length;b<c;b++){
            t=h[b*f+d];
            if(j&&a.aoColumns[d].sType!="string"){
                q=H(a,b,d,"type");
                if(q!==""){
                    q=fa(q);
                    if(a.aoColumns[d].sType===null)a.aoColumns[d].sType=q;
                    else if(a.aoColumns[d].sType!=q)a.aoColumns[d].sType="string"
                        }
                    }
            if(m){
            q=a.aoColumns[d].fnRender({
                iDataRow:b,
                iDataColumn:d,
                aData:a.aoData[b]._aData,
                oSettings:a
            });
            t.innerHTML=q;
            a.aoColumns[d].bUseRendered&&
            N(a,b,d,q)
            }
            if(e)t.className+=" "+a.aoColumns[d].sClass;
            if(k)a.aoData[b]._anHidden[d]=null;
            else{
            a.aoData[b]._anHidden[d]=t;
            t.parentNode.removeChild(t)
            }
        }
    }
}
}
function V(a){
    var b,c,d;
    a.nTHead.getElementsByTagName("tr");
    if(a.nTHead.getElementsByTagName("th").length!==0){
        b=0;
        for(d=a.aoColumns.length;b<d;b++){
            c=a.aoColumns[b].nTh;
            a.aoColumns[b].sClass!==null&&i(c).addClass(a.aoColumns[b].sClass);
            if(a.aoColumns[b].sTitle!=c.innerHTML)c.innerHTML=a.aoColumns[b].sTitle
                }
            }else{
    var f=p.createElement("tr");
    b=0;
    for(d=a.aoColumns.length;b<d;b++){
        c=a.aoColumns[b].nTh;
        c.innerHTML=a.aoColumns[b].sTitle;
        a.aoColumns[b].sClass!==null&&i(c).addClass(a.aoColumns[b].sClass);
        f.appendChild(c)
        }
        i(a.nTHead).html("")[0].appendChild(f);
    W(a.aoHeader,a.nTHead)
    }
    if(a.bJUI){
    b=0;
    for(d=a.aoColumns.length;b<d;b++){
        c=a.aoColumns[b].nTh;
        f=p.createElement("div");
        f.className=a.oClasses.sSortJUIWrapper;
        i(c).contents().appendTo(f);
        var e=p.createElement("span");
        e.className=a.oClasses.sSortIcon;
        f.appendChild(e);
        c.appendChild(f)
        }
    }
    d=function(){
    this.onselectstart=
    function(){
        return false
        };
        
    return false
    };
    
if(a.oFeatures.bSort)for(b=0;b<a.aoColumns.length;b++)if(a.aoColumns[b].bSortable!==false){
    ga(a,a.aoColumns[b].nTh,b);
    i(a.aoColumns[b].nTh).bind("mousedown.DT",d)
    }else i(a.aoColumns[b].nTh).addClass(a.oClasses.sSortableNone);a.oClasses.sFooterTH!==""&&i(">tr>th",a.nTFoot).addClass(a.oClasses.sFooterTH);
if(a.nTFoot!==null){
    c=S(a,null,a.aoFooter);
    b=0;
    for(d=a.aoColumns.length;b<d;b++)if(typeof c[b]!="undefined")a.aoColumns[b].nTf=c[b]
        }
    }
function L(a,b,c){
    var d,f,
    e,h=[],j=[],k=a.aoColumns.length;
    if(typeof c=="undefined")c=false;
    d=0;
    for(f=b.length;d<f;d++){
        h[d]=b[d].slice();
        h[d].nTr=b[d].nTr;
        for(e=k-1;e>=0;e--)!a.aoColumns[e].bVisible&&!c&&h[d].splice(e,1);
        j.push([])
        }
        d=0;
    for(f=h.length;d<f;d++){
        if(h[d].nTr){
            a=0;
            for(e=h[d].nTr.childNodes.length;a<e;a++)h[d].nTr.removeChild(h[d].nTr.childNodes[0])
                }
                e=0;
        for(b=h[d].length;e<b;e++){
            k=c=1;
            if(typeof j[d][e]=="undefined"){
                h[d].nTr.appendChild(h[d][e].cell);
                for(j[d][e]=1;typeof h[d+c]!="undefined"&&h[d][e].cell==h[d+
                    c][e].cell;){
                    j[d+c][e]=1;
                    c++
                }
                for(;typeof h[d][e+k]!="undefined"&&h[d][e].cell==h[d][e+k].cell;){
                    for(a=0;a<c;a++)j[d+a][e+k]=1;
                    k++
                }
                h[d][e].cell.setAttribute("rowspan",c);
                h[d][e].cell.setAttribute("colspan",k)
                }
            }
        }
    }
function C(a){
    var b,c,d=[],f=0,e=false;
    b=a.asStripClasses.length;
    c=a.aoOpenRows.length;
    if(!(a.fnPreDrawCallback!==null&&a.fnPreDrawCallback.call(a.oInstance,a)===false)){
        a.bDrawing=true;
        if(typeof a.iInitDisplayStart!="undefined"&&a.iInitDisplayStart!=-1){
            a._iDisplayStart=a.oFeatures.bServerSide?
            a.iInitDisplayStart:a.iInitDisplayStart>=a.fnRecordsDisplay()?0:a.iInitDisplayStart;
            a.iInitDisplayStart=-1;
            E(a)
            }
            if(a.bDeferLoading){
            a.bDeferLoading=false;
            a.iDraw++
        }else if(a.oFeatures.bServerSide){
            if(!a.bDestroying&&!za(a))return
        }else a.iDraw++;
        if(a.aiDisplay.length!==0){
            var h=a._iDisplayStart,j=a._iDisplayEnd;
            if(a.oFeatures.bServerSide){
                h=0;
                j=a.aoData.length
                }
                for(h=h;h<j;h++){
                var k=a.aoData[a.aiDisplay[h]];
                k.nTr===null&&z(a,a.aiDisplay[h]);
                var m=k.nTr;
                if(b!==0){
                    var t=a.asStripClasses[f%b];
                    if(k._sRowStripe!=
                        t){
                        i(m).removeClass(k._sRowStripe).addClass(t);
                        k._sRowStripe=t
                        }
                    }
                if(typeof a.fnRowCallback=="function"){
                m=a.fnRowCallback.call(a.oInstance,m,a.aoData[a.aiDisplay[h]]._aData,f,h);
                if(!m&&!e){
                    J(a,0,"A node was not returned by fnRowCallback");
                    e=true
                    }
                }
            d.push(m);
            f++;
            if(c!==0)for(k=0;k<c;k++)m==a.aoOpenRows[k].nParent&&d.push(a.aoOpenRows[k].nTr)
                }
            }else{
    d[0]=p.createElement("tr");
    if(typeof a.asStripClasses[0]!="undefined")d[0].className=a.asStripClasses[0];
    e=a.oLanguage.sZeroRecords.replace("_MAX_",a.fnFormatNumber(a.fnRecordsTotal()));
    if(a.iDraw==1&&a.sAjaxSource!==null&&!a.oFeatures.bServerSide)e=a.oLanguage.sLoadingRecords;
    else if(typeof a.oLanguage.sEmptyTable!="undefined"&&a.fnRecordsTotal()===0)e=a.oLanguage.sEmptyTable;
    b=p.createElement("td");
    b.setAttribute("valign","top");
    b.colSpan=X(a);
    b.className=a.oClasses.sRowEmpty;
    b.innerHTML=e;
    d[f].appendChild(b)
    }
    typeof a.fnHeaderCallback=="function"&&a.fnHeaderCallback.call(a.oInstance,i(">tr",a.nTHead)[0],aa(a),a._iDisplayStart,a.fnDisplayEnd(),a.aiDisplay);
typeof a.fnFooterCallback==
"function"&&a.fnFooterCallback.call(a.oInstance,i(">tr",a.nTFoot)[0],aa(a),a._iDisplayStart,a.fnDisplayEnd(),a.aiDisplay);
f=p.createDocumentFragment();
b=p.createDocumentFragment();
if(a.nTBody){
    e=a.nTBody.parentNode;
    b.appendChild(a.nTBody);
    if(!a.oScroll.bInfinite||!a._bInitComplete||a.bSorted||a.bFiltered){
        c=a.nTBody.childNodes;
        for(b=c.length-1;b>=0;b--)c[b].parentNode.removeChild(c[b])
            }
            b=0;
    for(c=d.length;b<c;b++)f.appendChild(d[b]);
    a.nTBody.appendChild(f);
    e!==null&&e.appendChild(a.nTBody)
    }
    for(b=a.aoDrawCallback.length-
    1;b>=0;b--)a.aoDrawCallback[b].fn.call(a.oInstance,a);
a.bSorted=false;
a.bFiltered=false;
a.bDrawing=false;
if(a.oFeatures.bServerSide){
    K(a,false);
    typeof a._bInitComplete=="undefined"&&w(a)
    }
}
}
function ba(a){
    if(a.oFeatures.bSort)R(a,a.oPreviousSearch);
    else if(a.oFeatures.bFilter)M(a,a.oPreviousSearch);
    else{
        E(a);
        C(a)
        }
    }
function za(a){
    if(a.bAjaxDataGet){
        K(a,true);
        var b=a.aoColumns.length,c=[],d,f;
        a.iDraw++;
        c.push({
            name:"sEcho",
            value:a.iDraw
            });
        c.push({
            name:"iColumns",
            value:b
        });
        c.push({
            name:"sColumns",
            value:ha(a)
            });
        c.push({
            name:"iDisplayStart",
            value:a._iDisplayStart
            });
        c.push({
            name:"iDisplayLength",
            value:a.oFeatures.bPaginate!==false?a._iDisplayLength:-1
            });
        for(f=0;f<b;f++){
            d=a.aoColumns[f].mDataProp;
            c.push({
                name:"mDataProp_"+f,
                value:typeof d=="function"?"function":d
                })
            }
            if(a.oFeatures.bFilter!==false){
            c.push({
                name:"sSearch",
                value:a.oPreviousSearch.sSearch
                });
            c.push({
                name:"bRegex",
                value:a.oPreviousSearch.bRegex
                });
            for(f=0;f<b;f++){
                c.push({
                    name:"sSearch_"+f,
                    value:a.aoPreSearchCols[f].sSearch
                    });
                c.push({
                    name:"bRegex_"+
                    f,
                    value:a.aoPreSearchCols[f].bRegex
                    });
                c.push({
                    name:"bSearchable_"+f,
                    value:a.aoColumns[f].bSearchable
                    })
                }
            }
            if(a.oFeatures.bSort!==false){
        d=a.aaSortingFixed!==null?a.aaSortingFixed.length:0;
        var e=a.aaSorting.length;
        c.push({
            name:"iSortingCols",
            value:d+e
            });
        for(f=0;f<d;f++){
            c.push({
                name:"iSortCol_"+f,
                value:a.aaSortingFixed[f][0]
                });
            c.push({
                name:"sSortDir_"+f,
                value:a.aaSortingFixed[f][1]
                })
            }
            for(f=0;f<e;f++){
            c.push({
                name:"iSortCol_"+(f+d),
                value:a.aaSorting[f][0]
                });
            c.push({
                name:"sSortDir_"+(f+d),
                value:a.aaSorting[f][1]
                })
            }
            for(f=
            0;f<b;f++)c.push({
            name:"bSortable_"+f,
            value:a.aoColumns[f].bSortable
            })
        }
        a.fnServerData.call(a.oInstance,a.sAjaxSource,c,function(h){
        Aa(a,h)
        },a);
    return false
    }else return true
    }
    function Aa(a,b){
    if(typeof b.sEcho!="undefined")if(b.sEcho*1<a.iDraw)return;else a.iDraw=b.sEcho*1;
    if(!a.oScroll.bInfinite||a.oScroll.bInfinite&&(a.bSorted||a.bFiltered))ia(a);
    a._iRecordsTotal=b.iTotalRecords;
    a._iRecordsDisplay=b.iTotalDisplayRecords;
    var c=ha(a);
    if(c=typeof b.sColumns!="undefined"&&c!==""&&b.sColumns!=c)var d=
        Ba(a,b.sColumns);
    b=Z(a.sAjaxDataProp)(b);
    for(var f=0,e=b.length;f<e;f++)if(c){
        for(var h=[],j=0,k=a.aoColumns.length;j<k;j++)h.push(b[f][d[j]]);
        v(a,h)
        }else v(a,b[f]);a.aiDisplay=a.aiDisplayMaster.slice();
    a.bAjaxDataGet=false;
    C(a);
    a.bAjaxDataGet=true;
    K(a,false)
    }
    function xa(a){
    var b=p.createElement("div");
    a.nTable.parentNode.insertBefore(b,a.nTable);
    a.nTableWrapper=p.createElement("div");
    a.nTableWrapper.className=a.oClasses.sWrapper;
    a.sTableId!==""&&a.nTableWrapper.setAttribute("id",a.sTableId+"_wrapper");
    a.nTableReinsertBefore=a.nTable.nextSibling;
    for(var c=a.nTableWrapper,d=a.sDom.split(""),f,e,h,j,k,m,t,q=0;q<d.length;q++){
        e=0;
        h=d[q];
        if(h=="<"){
            j=p.createElement("div");
            k=d[q+1];
            if(k=="'"||k=='"'){
                m="";
                for(t=2;d[q+t]!=k;){
                    m+=d[q+t];
                    t++
                }
                if(m=="H")m="fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix";
                else if(m=="F")m="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix";
                if(m.indexOf(".")!=-1){
                    k=m.split(".");
                    j.setAttribute("id",k[0].substr(1,
                        k[0].length-1));
                    j.className=k[1]
                    }else if(m.charAt(0)=="#")j.setAttribute("id",m.substr(1,m.length-1));else j.className=m;
                q+=t
                }
                c.appendChild(j);
            c=j
            }else if(h==">")c=c.parentNode;
        else if(h=="l"&&a.oFeatures.bPaginate&&a.oFeatures.bLengthChange){
            f=Ca(a);
            e=1
            }else if(h=="f"&&a.oFeatures.bFilter){
            f=Da(a);
            e=1
            }else if(h=="r"&&a.oFeatures.bProcessing){
            f=Ea(a);
            e=1
            }else if(h=="t"){
            f=Fa(a);
            e=1
            }else if(h=="i"&&a.oFeatures.bInfo){
            f=Ga(a);
            e=1
            }else if(h=="p"&&a.oFeatures.bPaginate){
            f=Ha(a);
            e=1
            }else if(o.aoFeatures.length!==
            0){
            j=o.aoFeatures;
            t=0;
            for(k=j.length;t<k;t++)if(h==j[t].cFeature){
                if(f=j[t].fnInit(a))e=1;
                break
            }
            }
            if(e==1&&f!==null){
        if(typeof a.aanFeatures[h]!="object")a.aanFeatures[h]=[];
        a.aanFeatures[h].push(f);
        c.appendChild(f)
        }
    }
    b.parentNode.replaceChild(a.nTableWrapper,b)
}
function Fa(a){
    if(a.oScroll.sX===""&&a.oScroll.sY==="")return a.nTable;
    var b=p.createElement("div"),c=p.createElement("div"),d=p.createElement("div"),f=p.createElement("div"),e=p.createElement("div"),h=p.createElement("div"),j=a.nTable.cloneNode(false),
    k=a.nTable.cloneNode(false),m=a.nTable.getElementsByTagName("thead")[0],t=a.nTable.getElementsByTagName("tfoot").length===0?null:a.nTable.getElementsByTagName("tfoot")[0],q=typeof g.bJQueryUI!="undefined"&&g.bJQueryUI?o.oJUIClasses:o.oStdClasses;
    c.appendChild(d);
    e.appendChild(h);
    f.appendChild(a.nTable);
    b.appendChild(c);
    b.appendChild(f);
    d.appendChild(j);
    j.appendChild(m);
    if(t!==null){
        b.appendChild(e);
        h.appendChild(k);
        k.appendChild(t)
        }
        b.className=q.sScrollWrapper;
    c.className=q.sScrollHead;
    d.className=
    q.sScrollHeadInner;
    f.className=q.sScrollBody;
    e.className=q.sScrollFoot;
    h.className=q.sScrollFootInner;
    if(a.oScroll.bAutoCss){
        c.style.overflow="hidden";
        c.style.position="relative";
        e.style.overflow="hidden";
        f.style.overflow="auto"
        }
        c.style.border="0";
    c.style.width="100%";
    e.style.border="0";
    d.style.width="150%";
    j.removeAttribute("id");
    j.style.marginLeft="0";
    a.nTable.style.marginLeft="0";
    if(t!==null){
        k.removeAttribute("id");
        k.style.marginLeft="0"
        }
        d=i(">caption",a.nTable);
    h=0;
    for(k=d.length;h<k;h++)j.appendChild(d[h]);
    if(a.oScroll.sX!==""){
        c.style.width=u(a.oScroll.sX);
        f.style.width=u(a.oScroll.sX);
        if(t!==null)e.style.width=u(a.oScroll.sX);
        i(f).scroll(function(){
            c.scrollLeft=this.scrollLeft;
            if(t!==null)e.scrollLeft=this.scrollLeft
                })
        }
        if(a.oScroll.sY!=="")f.style.height=u(a.oScroll.sY);
    a.aoDrawCallback.push({
        fn:Ia,
        sName:"scrolling"
    });
    a.oScroll.bInfinite&&i(f).scroll(function(){
        if(!a.bDrawing)if(i(this).scrollTop()+i(this).height()>i(a.nTable).height()-a.oScroll.iLoadGap)if(a.fnDisplayEnd()<a.fnRecordsDisplay()){
            ja(a,
                "next");
            E(a);
            C(a)
            }
        });
a.nScrollHead=c;
a.nScrollFoot=e;
return b
}
function Ia(a){
    var b=a.nScrollHead.getElementsByTagName("div")[0],c=b.getElementsByTagName("table")[0],d=a.nTable.parentNode,f,e,h,j,k,m,t,q,I=[];
    h=a.nTable.getElementsByTagName("thead");
    h.length>0&&a.nTable.removeChild(h[0]);
    if(a.nTFoot!==null){
        k=a.nTable.getElementsByTagName("tfoot");
        k.length>0&&a.nTable.removeChild(k[0])
        }
        h=a.nTHead.cloneNode(true);
    a.nTable.insertBefore(h,a.nTable.childNodes[0]);
    if(a.nTFoot!==null){
        k=a.nTFoot.cloneNode(true);
        a.nTable.insertBefore(k,a.nTable.childNodes[1])
        }
        if(a.oScroll.sX===""){
        d.style.width="100%";
        b.parentNode.style.width="100%"
        }
        var O=S(a,h);
    f=0;
    for(e=O.length;f<e;f++){
        t=Ja(a,f);
        O[f].style.width=a.aoColumns[t].sWidth
        }
        a.nTFoot!==null&&P(function(B){
        B.style.width=""
        },k.getElementsByTagName("tr"));
    f=i(a.nTable).outerWidth();
    if(a.oScroll.sX===""){
        a.nTable.style.width="100%";
        if(i.browser.msie&&i.browser.version<=7)a.nTable.style.width=u(i(a.nTable).outerWidth()-a.oScroll.iBarWidth)
            }else if(a.oScroll.sXInner!==
        "")a.nTable.style.width=u(a.oScroll.sXInner);
    else if(f==i(d).width()&&i(d).height()<i(a.nTable).height()){
        a.nTable.style.width=u(f-a.oScroll.iBarWidth);
        if(i(a.nTable).outerWidth()>f-a.oScroll.iBarWidth)a.nTable.style.width=u(f)
            }else a.nTable.style.width=u(f);
    f=i(a.nTable).outerWidth();
    if(a.oScroll.sX===""){
        d.style.width=u(f+a.oScroll.iBarWidth);
        b.parentNode.style.width=u(f+a.oScroll.iBarWidth)
        }
        e=a.nTHead.getElementsByTagName("tr");
    h=h.getElementsByTagName("tr");
    P(function(B,F){
        m=B.style;
        m.paddingTop=
        "0";
        m.paddingBottom="0";
        m.borderTopWidth="0";
        m.borderBottomWidth="0";
        m.height=0;
        q=i(B).width();
        F.style.width=u(q);
        I.push(q)
        },h,e);
    i(h).height(0);
    if(a.nTFoot!==null){
        j=k.getElementsByTagName("tr");
        k=a.nTFoot.getElementsByTagName("tr");
        P(function(B,F){
            m=B.style;
            m.paddingTop="0";
            m.paddingBottom="0";
            m.borderTopWidth="0";
            m.borderBottomWidth="0";
            m.height=0;
            q=i(B).width();
            F.style.width=u(q);
            I.push(q)
            },j,k);
        i(j).height(0)
        }
        P(function(B){
        B.innerHTML="";
        B.style.width=u(I.shift())
        },h);
    a.nTFoot!==null&&P(function(B){
        B.innerHTML=
        "";
        B.style.width=u(I.shift())
        },j);
    if(i(a.nTable).outerWidth()<f)if(a.oScroll.sX==="")J(a,1,"The table cannot fit into the current element which will cause column misalignment. It is suggested that you enable x-scrolling or increase the width the table has in which to be drawn");else a.oScroll.sXInner!==""&&J(a,1,"The table cannot fit into the current element which will cause column misalignment. It is suggested that you increase the sScrollXInner property to allow it to draw in a larger area, or simply remove that parameter to allow automatic calculation");
    if(a.oScroll.sY==="")if(i.browser.msie&&i.browser.version<=7)d.style.height=u(a.nTable.offsetHeight+a.oScroll.iBarWidth);
    if(a.oScroll.sY!==""&&a.oScroll.bCollapse){
        d.style.height=u(a.oScroll.sY);
        j=a.oScroll.sX!==""&&a.nTable.offsetWidth>d.offsetWidth?a.oScroll.iBarWidth:0;
        if(a.nTable.offsetHeight<d.offsetHeight)d.style.height=u(i(a.nTable).height()+j)
            }
            j=i(a.nTable).outerWidth();
    c.style.width=u(j);
    b.style.width=u(j+a.oScroll.iBarWidth);
    if(a.nTFoot!==null){
        b=a.nScrollFoot.getElementsByTagName("div")[0];
        c=b.getElementsByTagName("table")[0];
        b.style.width=u(a.nTable.offsetWidth+a.oScroll.iBarWidth);
        c.style.width=u(a.nTable.offsetWidth)
        }
        if(a.bSorted||a.bFiltered)d.scrollTop=0
        }
        function ca(a){
    if(a.oFeatures.bAutoWidth===false)return false;
    ea(a);
    for(var b=0,c=a.aoColumns.length;b<c;b++)a.aoColumns[b].nTh.style.width=a.aoColumns[b].sWidth
        }
        function Da(a){
    var b=a.oLanguage.sSearch;
    b=b.indexOf("_INPUT_")!==-1?b.replace("_INPUT_",'<input type="text" />'):b===""?'<input type="text" />':b+' <input type="text" />';
    var c=p.createElement("div");
    c.className=a.oClasses.sFilter;
    c.innerHTML="<label>"+b+"</label>";
    a.sTableId!==""&&typeof a.aanFeatures.f=="undefined"&&c.setAttribute("id",a.sTableId+"_filter");
    b=i("input",c);
    b.val(a.oPreviousSearch.sSearch.replace('"',"&quot;"));
    b.bind("keyup.DT",function(){
        for(var d=a.aanFeatures.f,f=0,e=d.length;f<e;f++)d[f]!=this.parentNode&&i("input",d[f]).val(this.value);
        this.value!=a.oPreviousSearch.sSearch&&M(a,{
            sSearch:this.value,
            bRegex:a.oPreviousSearch.bRegex,
            bSmart:a.oPreviousSearch.bSmart
            })
        });
    b.bind("keypress.DT",function(d){
        if(d.keyCode==13)return false
            });
    return c
    }
    function M(a,b,c){
    Ka(a,b.sSearch,c,b.bRegex,b.bSmart);
    for(b=0;b<a.aoPreSearchCols.length;b++)La(a,a.aoPreSearchCols[b].sSearch,b,a.aoPreSearchCols[b].bRegex,a.aoPreSearchCols[b].bSmart);
    o.afnFiltering.length!==0&&Ma(a);
    a.bFiltered=true;
    a._iDisplayStart=0;
    E(a);
    C(a);
    ka(a,0)
    }
    function Ma(a){
    for(var b=o.afnFiltering,c=0,d=b.length;c<d;c++)for(var f=0,e=0,h=a.aiDisplay.length;e<h;e++){
        var j=a.aiDisplay[e-f];
        if(!b[c](a,da(a,j,"filter"),
            j)){
            a.aiDisplay.splice(e-f,1);
            f++
        }
    }
    }
    function La(a,b,c,d,f){
    if(b!==""){
        var e=0;
        b=la(b,d,f);
        for(d=a.aiDisplay.length-1;d>=0;d--){
            f=ma(H(a,a.aiDisplay[d],c,"filter"),a.aoColumns[c].sType);
            if(!b.test(f)){
                a.aiDisplay.splice(d,1);
                e++
            }
        }
        }
}
function Ka(a,b,c,d,f){
    var e=la(b,d,f);
    if(typeof c=="undefined"||c===null)c=0;
    if(o.afnFiltering.length!==0)c=1;
    if(b.length<=0){
        a.aiDisplay.splice(0,a.aiDisplay.length);
        a.aiDisplay=a.aiDisplayMaster.slice()
        }else if(a.aiDisplay.length==a.aiDisplayMaster.length||a.oPreviousSearch.sSearch.length>
        b.length||c==1||b.indexOf(a.oPreviousSearch.sSearch)!==0){
        a.aiDisplay.splice(0,a.aiDisplay.length);
        ka(a,1);
        for(c=0;c<a.aiDisplayMaster.length;c++)e.test(a.asDataSearch[c])&&a.aiDisplay.push(a.aiDisplayMaster[c])
            }else{
        var h=0;
        for(c=0;c<a.asDataSearch.length;c++)if(!e.test(a.asDataSearch[c])){
            a.aiDisplay.splice(c-h,1);
            h++
        }
        }
        a.oPreviousSearch.sSearch=b;
a.oPreviousSearch.bRegex=d;
a.oPreviousSearch.bSmart=f
}
function ka(a,b){
    a.asDataSearch.splice(0,a.asDataSearch.length);
    b=typeof b!="undefined"&&b==1?a.aiDisplayMaster:
    a.aiDisplay;
    for(var c=0,d=b.length;c<d;c++)a.asDataSearch[c]=na(a,da(a,b[c],"filter"))
        }
        function na(a,b){
    var c="";
    if(typeof a.__nTmpFilter=="undefined")a.__nTmpFilter=p.createElement("div");
    for(var d=a.__nTmpFilter,f=0,e=a.aoColumns.length;f<e;f++)if(a.aoColumns[f].bSearchable)c+=ma(b[f],a.aoColumns[f].sType)+"  ";if(c.indexOf("&")!==-1){
        d.innerHTML=c;
        c=d.textContent?d.textContent:d.innerText;
        c=c.replace(/\n/g," ").replace(/\r/g,"")
        }
        return c
    }
    function la(a,b,c){
    if(c){
        a=b?a.split(" "):oa(a).split(" ");
        a="^(?=.*?"+a.join(")(?=.*?")+").*$";
        return new RegExp(a,"i")
        }else{
        a=b?a:oa(a);
        return new RegExp(a,"i")
        }
    }
function ma(a,b){
    if(typeof o.ofnSearch[b]=="function")return o.ofnSearch[b](a);
    else if(b=="html")return a.replace(/\n/g," ").replace(/<.*?>/g,"");
    else if(typeof a=="string")return a.replace(/\n/g," ");
    else if(a===null)return"";
    return a
    }
    function R(a,b){
    var c,d,f,e,h=[],j=[],k=o.oSort;
    d=a.aoData;
    var m=a.aoColumns;
    if(!a.oFeatures.bServerSide&&(a.aaSorting.length!==0||a.aaSortingFixed!==null)){
        h=a.aaSortingFixed!==
        null?a.aaSortingFixed.concat(a.aaSorting):a.aaSorting.slice();
        for(c=0;c<h.length;c++){
            var t=h[c][0];
            f=pa(a,t);
            e=a.aoColumns[t].sSortDataType;
            if(typeof o.afnSortData[e]!="undefined"){
                var q=o.afnSortData[e](a,t,f);
                f=0;
                for(e=d.length;f<e;f++)N(a,f,t,q[f])
                    }
                }
        c=0;
    for(d=a.aiDisplayMaster.length;c<d;c++)j[a.aiDisplayMaster[c]]=c;
    var I=h.length;
    a.aiDisplayMaster.sort(function(O,B){
        var F,qa;
        for(c=0;c<I;c++){
            F=m[h[c][0]].iDataSort;
            qa=m[F].sType;
            F=k[(qa?qa:"string")+"-"+h[c][1]](H(a,O,F,"sort"),H(a,B,F,"sort"));
            if(F!==0)return F
                }
                return k["numeric-asc"](j[O],j[B])
        })
    }
    if((typeof b=="undefined"||b)&&!a.oFeatures.bDeferRender)T(a);
a.bSorted=true;
if(a.oFeatures.bFilter)M(a,a.oPreviousSearch,1);
else{
    a.aiDisplay=a.aiDisplayMaster.slice();
    a._iDisplayStart=0;
    E(a);
    C(a)
    }
}
function ga(a,b,c,d){
    i(b).bind("click.DT",function(f){
        if(a.aoColumns[c].bSortable!==false){
            var e=function(){
                var h,j;
                if(f.shiftKey){
                    for(var k=false,m=0;m<a.aaSorting.length;m++)if(a.aaSorting[m][0]==c){
                        k=true;
                        h=a.aaSorting[m][0];
                        j=a.aaSorting[m][2]+
                        1;
                        if(typeof a.aoColumns[h].asSorting[j]=="undefined")a.aaSorting.splice(m,1);
                        else{
                            a.aaSorting[m][1]=a.aoColumns[h].asSorting[j];
                            a.aaSorting[m][2]=j
                            }
                            break
                    }
                    k===false&&a.aaSorting.push([c,a.aoColumns[c].asSorting[0],0])
                    }else if(a.aaSorting.length==1&&a.aaSorting[0][0]==c){
                    h=a.aaSorting[0][0];
                    j=a.aaSorting[0][2]+1;
                    if(typeof a.aoColumns[h].asSorting[j]=="undefined")j=0;
                    a.aaSorting[0][1]=a.aoColumns[h].asSorting[j];
                    a.aaSorting[0][2]=j
                    }else{
                    a.aaSorting.splice(0,a.aaSorting.length);
                    a.aaSorting.push([c,a.aoColumns[c].asSorting[0],
                        0])
                    }
                    R(a)
                };
                
            if(a.oFeatures.bProcessing){
                K(a,true);
                setTimeout(function(){
                    e();
                    a.oFeatures.bServerSide||K(a,false)
                    },0)
                }else e();
            typeof d=="function"&&d(a)
            }
        })
}
function T(a){
    var b,c,d,f,e,h=a.aoColumns.length,j=a.oClasses;
    for(b=0;b<h;b++)a.aoColumns[b].bSortable&&i(a.aoColumns[b].nTh).removeClass(j.sSortAsc+" "+j.sSortDesc+" "+a.aoColumns[b].sSortingClass);
    f=a.aaSortingFixed!==null?a.aaSortingFixed.concat(a.aaSorting):a.aaSorting.slice();
    for(b=0;b<a.aoColumns.length;b++)if(a.aoColumns[b].bSortable){
        e=a.aoColumns[b].sSortingClass;
        d=-1;
        for(c=0;c<f.length;c++)if(f[c][0]==b){
            e=f[c][1]=="asc"?j.sSortAsc:j.sSortDesc;
            d=c;
            break
        }
        i(a.aoColumns[b].nTh).addClass(e);
        if(a.bJUI){
            c=i("span",a.aoColumns[b].nTh);
            c.removeClass(j.sSortJUIAsc+" "+j.sSortJUIDesc+" "+j.sSortJUI+" "+j.sSortJUIAscAllowed+" "+j.sSortJUIDescAllowed);
            c.addClass(d==-1?a.aoColumns[b].sSortingClassJUI:f[d][1]=="asc"?j.sSortJUIAsc:j.sSortJUIDesc)
            }
        }else i(a.aoColumns[b].nTh).addClass(a.aoColumns[b].sSortingClass);e=j.sSortColumn;
if(a.oFeatures.bSort&&a.oFeatures.bSortClasses){
    d=
    Q(a);
    if(a.oFeatures.bDeferRender)i(d).removeClass(e+"1 "+e+"2 "+e+"3");
    else if(d.length>=h)for(b=0;b<h;b++)if(d[b].className.indexOf(e+"1")!=-1){
        c=0;
        for(a=d.length/h;c<a;c++)d[h*c+b].className=i.trim(d[h*c+b].className.replace(e+"1",""))
            }else if(d[b].className.indexOf(e+"2")!=-1){
        c=0;
        for(a=d.length/h;c<a;c++)d[h*c+b].className=i.trim(d[h*c+b].className.replace(e+"2",""))
            }else if(d[b].className.indexOf(e+"3")!=-1){
        c=0;
        for(a=d.length/h;c<a;c++)d[h*c+b].className=i.trim(d[h*c+b].className.replace(" "+
            e+"3",""))
        }
        j=1;
    var k;
    for(b=0;b<f.length;b++){
        k=parseInt(f[b][0],10);
        c=0;
        for(a=d.length/h;c<a;c++)d[h*c+k].className+=" "+e+j;
        j<3&&j++
    }
    }
}
function Ha(a){
    if(a.oScroll.bInfinite)return null;
    var b=p.createElement("div");
    b.className=a.oClasses.sPaging+a.sPaginationType;
    o.oPagination[a.sPaginationType].fnInit(a,b,function(c){
        E(c);
        C(c)
        });
    typeof a.aanFeatures.p=="undefined"&&a.aoDrawCallback.push({
        fn:function(c){
            o.oPagination[c.sPaginationType].fnUpdate(c,function(d){
                E(d);
                C(d)
                })
            },
        sName:"pagination"
    });
    return b
    }
function ja(a,b){
    var c=a._iDisplayStart;
    if(b=="first")a._iDisplayStart=0;
    else if(b=="previous"){
        a._iDisplayStart=a._iDisplayLength>=0?a._iDisplayStart-a._iDisplayLength:0;
        if(a._iDisplayStart<0)a._iDisplayStart=0
            }else if(b=="next")if(a._iDisplayLength>=0){
        if(a._iDisplayStart+a._iDisplayLength<a.fnRecordsDisplay())a._iDisplayStart+=a._iDisplayLength
            }else a._iDisplayStart=0;
    else if(b=="last")if(a._iDisplayLength>=0){
        b=parseInt((a.fnRecordsDisplay()-1)/a._iDisplayLength,10)+1;
        a._iDisplayStart=(b-1)*a._iDisplayLength
        }else a._iDisplayStart=
        0;else J(a,0,"Unknown paging action: "+b);
    return c!=a._iDisplayStart
    }
    function Ga(a){
    var b=p.createElement("div");
    b.className=a.oClasses.sInfo;
    if(typeof a.aanFeatures.i=="undefined"){
        a.aoDrawCallback.push({
            fn:Na,
            sName:"information"
        });
        a.sTableId!==""&&b.setAttribute("id",a.sTableId+"_info")
        }
        return b
    }
    function Na(a){
    if(!(!a.oFeatures.bInfo||a.aanFeatures.i.length===0)){
        var b=a._iDisplayStart+1,c=a.fnDisplayEnd(),d=a.fnRecordsTotal(),f=a.fnRecordsDisplay(),e=a.fnFormatNumber(b),h=a.fnFormatNumber(c),j=
        a.fnFormatNumber(d),k=a.fnFormatNumber(f);
        if(a.oScroll.bInfinite)e=a.fnFormatNumber(1);
        e=a.fnRecordsDisplay()===0&&a.fnRecordsDisplay()==a.fnRecordsTotal()?a.oLanguage.sInfoEmpty+a.oLanguage.sInfoPostFix:a.fnRecordsDisplay()===0?a.oLanguage.sInfoEmpty+" "+a.oLanguage.sInfoFiltered.replace("_MAX_",j)+a.oLanguage.sInfoPostFix:a.fnRecordsDisplay()==a.fnRecordsTotal()?a.oLanguage.sInfo.replace("_START_",e).replace("_END_",h).replace("_TOTAL_",k)+a.oLanguage.sInfoPostFix:a.oLanguage.sInfo.replace("_START_",
            e).replace("_END_",h).replace("_TOTAL_",k)+" "+a.oLanguage.sInfoFiltered.replace("_MAX_",a.fnFormatNumber(a.fnRecordsTotal()))+a.oLanguage.sInfoPostFix;
        if(a.oLanguage.fnInfoCallback!==null)e=a.oLanguage.fnInfoCallback(a,b,c,d,f,e);
        a=a.aanFeatures.i;
        b=0;
        for(c=a.length;b<c;b++)i(a[b]).html(e)
            }
        }
function Ca(a){
    if(a.oScroll.bInfinite)return null;
    var b='<select size="1" '+(a.sTableId===""?"":'name="'+a.sTableId+'_length"')+">",c,d;
    if(a.aLengthMenu.length==2&&typeof a.aLengthMenu[0]=="object"&&typeof a.aLengthMenu[1]==
        "object"){
        c=0;
        for(d=a.aLengthMenu[0].length;c<d;c++)b+='<option value="'+a.aLengthMenu[0][c]+'">'+a.aLengthMenu[1][c]+"</option>"
            }else{
        c=0;
        for(d=a.aLengthMenu.length;c<d;c++)b+='<option value="'+a.aLengthMenu[c]+'">'+a.aLengthMenu[c]+"</option>"
            }
            b+="</select>";
    var f=p.createElement("div");
    a.sTableId!==""&&typeof a.aanFeatures.l=="undefined"&&f.setAttribute("id",a.sTableId+"_length");
    f.className=a.oClasses.sLength;
    f.innerHTML="<label>"+a.oLanguage.sLengthMenu.replace("_MENU_",b)+"</label>";
    i('select option[value="'+
        a._iDisplayLength+'"]',f).attr("selected",true);
    i("select",f).bind("change.DT",function(){
        var e=i(this).val(),h=a.aanFeatures.l;
        c=0;
        for(d=h.length;c<d;c++)h[c]!=this.parentNode&&i("select",h[c]).val(e);
        a._iDisplayLength=parseInt(e,10);
        E(a);
        if(a.fnDisplayEnd()==a.fnRecordsDisplay()){
            a._iDisplayStart=a.fnDisplayEnd()-a._iDisplayLength;
            if(a._iDisplayStart<0)a._iDisplayStart=0
                }
                if(a._iDisplayLength==-1)a._iDisplayStart=0;
        C(a)
        });
    return f
    }
    function Ea(a){
    var b=p.createElement("div");
    a.sTableId!==""&&typeof a.aanFeatures.r==
    "undefined"&&b.setAttribute("id",a.sTableId+"_processing");
    b.innerHTML=a.oLanguage.sProcessing;
    b.className=a.oClasses.sProcessing;
    a.nTable.parentNode.insertBefore(b,a.nTable);
    return b
    }
    function K(a,b){
    if(a.oFeatures.bProcessing){
        a=a.aanFeatures.r;
        for(var c=0,d=a.length;c<d;c++)a[c].style.visibility=b?"visible":"hidden"
            }
        }
function Ja(a,b){
    for(var c=-1,d=0;d<a.aoColumns.length;d++){
        a.aoColumns[d].bVisible===true&&c++;
        if(c==b)return d
            }
            return null
    }
    function pa(a,b){
    for(var c=-1,d=0;d<a.aoColumns.length;d++){
        a.aoColumns[d].bVisible===
        true&&c++;
        if(d==b)return a.aoColumns[d].bVisible===true?c:null
            }
            return null
    }
    function U(a,b){
    var c,d;
    c=a._iDisplayStart;
    for(d=a._iDisplayEnd;c<d;c++)if(a.aoData[a.aiDisplay[c]].nTr==b)return a.aiDisplay[c];c=0;
    for(d=a.aoData.length;c<d;c++)if(a.aoData[c].nTr==b)return c;return null
    }
    function X(a){
    for(var b=0,c=0;c<a.aoColumns.length;c++)a.aoColumns[c].bVisible===true&&b++;
    return b
    }
    function E(a){
    a._iDisplayEnd=a.oFeatures.bPaginate===false?a.aiDisplay.length:a._iDisplayStart+a._iDisplayLength>a.aiDisplay.length||
    a._iDisplayLength==-1?a.aiDisplay.length:a._iDisplayStart+a._iDisplayLength
    }
    function Oa(a,b){
    if(!a||a===null||a==="")return 0;
    if(typeof b=="undefined")b=p.getElementsByTagName("body")[0];
    var c=p.createElement("div");
    c.style.width=u(a);
    b.appendChild(c);
    a=c.offsetWidth;
    b.removeChild(c);
    return a
    }
    function ea(a){
    var b=0,c,d=0,f=a.aoColumns.length,e,h=i("th",a.nTHead);
    for(e=0;e<f;e++)if(a.aoColumns[e].bVisible){
        d++;
        if(a.aoColumns[e].sWidth!==null){
            c=Oa(a.aoColumns[e].sWidthOrig,a.nTable.parentNode);
            if(c!==
                null)a.aoColumns[e].sWidth=u(c);
            b++
        }
    }
    if(f==h.length&&b===0&&d==f&&a.oScroll.sX===""&&a.oScroll.sY==="")for(e=0;e<a.aoColumns.length;e++){
    c=i(h[e]).width();
    if(c!==null)a.aoColumns[e].sWidth=u(c)
        }else{
    b=a.nTable.cloneNode(false);
    e=a.nTHead.cloneNode(true);
    d=p.createElement("tbody");
    c=p.createElement("tr");
    b.removeAttribute("id");
    b.appendChild(e);
    if(a.nTFoot!==null){
        b.appendChild(a.nTFoot.cloneNode(true));
        P(function(k){
            k.style.width=""
            },b.getElementsByTagName("tr"))
        }
        b.appendChild(d);
    d.appendChild(c);
    d=i("thead th",b);
    if(d.length===0)d=i("tbody tr:eq(0)>td",b);
    h=S(a,e);
    for(e=d=0;e<f;e++){
        var j=a.aoColumns[e];
        if(j.bVisible&&j.sWidthOrig!==null&&j.sWidthOrig!=="")h[e-d].style.width=u(j.sWidthOrig);
        else if(j.bVisible)h[e-d].style.width="";else d++
    }
    for(e=0;e<f;e++)if(a.aoColumns[e].bVisible){
        d=Pa(a,e);
        if(d!==null){
            d=d.cloneNode(true);
            if(a.aoColumns[e].sContentPadding!=="")d.innerHTML+=a.aoColumns[e].sContentPadding;
            c.appendChild(d)
            }
        }
    f=a.nTable.parentNode;
f.appendChild(b);
if(a.oScroll.sX!==""&&a.oScroll.sXInner!==
    "")b.style.width=u(a.oScroll.sXInner);
else if(a.oScroll.sX!==""){
    b.style.width="";
    if(i(b).width()<f.offsetWidth)b.style.width=u(f.offsetWidth)
        }else if(a.oScroll.sY!=="")b.style.width=u(f.offsetWidth);
b.style.visibility="hidden";
Qa(a,b);
f=i("tbody tr:eq(0)",b).children();
if(f.length===0)f=S(a,i("thead",b)[0]);
if(a.oScroll.sX!==""){
    for(e=d=c=0;e<a.aoColumns.length;e++)if(a.aoColumns[e].bVisible){
        c+=a.aoColumns[e].sWidthOrig===null?i(f[d]).outerWidth():parseInt(a.aoColumns[e].sWidth.replace("px",""),
            10)+(i(f[d]).outerWidth()-i(f[d]).width());
        d++
    }
    b.style.width=u(c);
    a.nTable.style.width=u(c)
    }
    for(e=d=0;e<a.aoColumns.length;e++)if(a.aoColumns[e].bVisible){
    c=i(f[d]).width();
    if(c!==null&&c>0)a.aoColumns[e].sWidth=u(c);
    d++
}
a.nTable.style.width=u(i(b).outerWidth());
b.parentNode.removeChild(b)
}
}
function Qa(a,b){
    if(a.oScroll.sX===""&&a.oScroll.sY!==""){
        i(b).width();
        b.style.width=u(i(b).outerWidth()-a.oScroll.iBarWidth)
        }else if(a.oScroll.sX!=="")b.style.width=u(i(b).outerWidth())
        }
        function Pa(a,b){
    var c=
    Ra(a,b);
    if(c<0)return null;
    if(a.aoData[c].nTr===null){
        var d=p.createElement("td");
        d.innerHTML=H(a,c,b,"");
        return d
        }
        return Q(a,c)[b]
    }
    function Ra(a,b){
    for(var c=-1,d=-1,f=0;f<a.aoData.length;f++){
        var e=H(a,f,b,"display")+"";
        e=e.replace(/<.*?>/g,"");
        if(e.length>c){
            c=e.length;
            d=f
            }
        }
    return d
}
function u(a){
    if(a===null)return"0px";
    if(typeof a=="number"){
        if(a<0)return"0px";
        return a+"px"
        }
        var b=a.charCodeAt(a.length-1);
    if(b<48||b>57)return a;
    return a+"px"
    }
    function Va(a,b){
    if(a.length!=b.length)return 1;
    for(var c=
        0;c<a.length;c++)if(a[c]!=b[c])return 2;return 0
    }
    function fa(a){
    for(var b=o.aTypes,c=b.length,d=0;d<c;d++){
        var f=b[d](a);
        if(f!==null)return f
            }
            return"string"
    }
    function A(a){
    for(var b=0;b<D.length;b++)if(D[b].nTable==a)return D[b];return null
    }
    function aa(a){
    for(var b=[],c=a.aoData.length,d=0;d<c;d++)b.push(a.aoData[d]._aData);
    return b
    }
    function $(a){
    for(var b=[],c=0,d=a.aoData.length;c<d;c++)a.aoData[c].nTr!==null&&b.push(a.aoData[c].nTr);
    return b
    }
    function Q(a,b){
    var c=[],d,f,e,h,j;
    f=0;
    var k=a.aoData.length;
    if(typeof b!="undefined"){
        f=b;
        k=b+1
        }
        for(f=f;f<k;f++){
        j=a.aoData[f];
        if(j.nTr!==null){
            b=[];
            e=0;
            for(h=j.nTr.childNodes.length;e<h;e++){
                d=j.nTr.childNodes[e].nodeName.toLowerCase();
                if(d=="td"||d=="th")b.push(j.nTr.childNodes[e])
                    }
                    e=d=0;
            for(h=a.aoColumns.length;e<h;e++)if(a.aoColumns[e].bVisible)c.push(b[e-d]);
                else{
                c.push(j._anHidden[e]);
                d++
            }
            }
        }
    return c
}
function oa(a){
    return a.replace(new RegExp("(\\/|\\.|\\*|\\+|\\?|\\||\\(|\\)|\\[|\\]|\\{|\\}|\\\\|\\$|\\^)","g"),"\\$1")
    }
    function ra(a,b){
    for(var c=-1,d=
        0,f=a.length;d<f;d++)if(a[d]==b)c=d;else a[d]>b&&a[d]--;c!=-1&&a.splice(c,1)
    }
    function Ba(a,b){
    b=b.split(",");
    for(var c=[],d=0,f=a.aoColumns.length;d<f;d++)for(var e=0;e<f;e++)if(a.aoColumns[d].sName==b[e]){
        c.push(e);
        break
    }
    return c
    }
    function ha(a){
    for(var b="",c=0,d=a.aoColumns.length;c<d;c++)b+=a.aoColumns[c].sName+",";
    if(b.length==d)return"";
    return b.slice(0,-1)
    }
    function J(a,b,c){
    a=a.sTableId===""?"DataTables warning: "+c:"DataTables warning (table id = '"+a.sTableId+"'): "+c;
    if(b===0)if(o.sErrMode==
        "alert")alert(a);else throw a;else typeof console!="undefined"&&typeof console.log!="undefined"&&console.log(a)
        }
        function ia(a){
    a.aoData.splice(0,a.aoData.length);
    a.aiDisplayMaster.splice(0,a.aiDisplayMaster.length);
    a.aiDisplay.splice(0,a.aiDisplay.length);
    E(a)
    }
    function sa(a){
    if(!(!a.oFeatures.bStateSave||typeof a.bDestroying!="undefined")){
        var b,c,d,f="{";
        f+='"iCreate":'+(new Date).getTime()+",";
        f+='"iStart":'+(a.oScroll.bInfinite?0:a._iDisplayStart)+",";
        f+='"iEnd":'+(a.oScroll.bInfinite?a._iDisplayLength:
            a._iDisplayEnd)+",";
        f+='"iLength":'+a._iDisplayLength+",";
        f+='"sFilter":"'+encodeURIComponent(a.oPreviousSearch.sSearch)+'",';
        f+='"sFilterEsc":'+!a.oPreviousSearch.bRegex+",";
        f+='"aaSorting":[ ';
        for(b=0;b<a.aaSorting.length;b++)f+="["+a.aaSorting[b][0]+',"'+a.aaSorting[b][1]+'"],';
        f=f.substring(0,f.length-1);
        f+="],";
        f+='"aaSearchCols":[ ';
        for(b=0;b<a.aoPreSearchCols.length;b++)f+='["'+encodeURIComponent(a.aoPreSearchCols[b].sSearch)+'",'+!a.aoPreSearchCols[b].bRegex+"],";
        f=f.substring(0,f.length-
            1);
        f+="],";
        f+='"abVisCols":[ ';
        for(b=0;b<a.aoColumns.length;b++)f+=a.aoColumns[b].bVisible+",";
        f=f.substring(0,f.length-1);
        f+="]";
        b=0;
        for(c=a.aoStateSave.length;b<c;b++){
            d=a.aoStateSave[b].fn(a,f);
            if(d!=="")f=d
                }
                f+="}";
        Sa(a.sCookiePrefix+a.sInstance,f,a.iCookieDuration,a.sCookiePrefix,a.fnCookieCallback)
        }
    }
function Ta(a,b){
    if(a.oFeatures.bStateSave){
        var c,d,f;
        d=ta(a.sCookiePrefix+a.sInstance);
        if(d!==null&&d!==""){
            try{
                c=typeof i.parseJSON=="function"?i.parseJSON(d.replace(/'/g,'"')):eval("("+d+")")
                }catch(e){
                return
            }
            d=
            0;
            for(f=a.aoStateLoad.length;d<f;d++)if(!a.aoStateLoad[d].fn(a,c))return;a.oLoadedState=i.extend(true,{
                },c);
            a._iDisplayStart=c.iStart;
            a.iInitDisplayStart=c.iStart;
            a._iDisplayEnd=c.iEnd;
            a._iDisplayLength=c.iLength;
            a.oPreviousSearch.sSearch=decodeURIComponent(c.sFilter);
            a.aaSorting=c.aaSorting.slice();
            a.saved_aaSorting=c.aaSorting.slice();
            if(typeof c.sFilterEsc!="undefined")a.oPreviousSearch.bRegex=!c.sFilterEsc;
            if(typeof c.aaSearchCols!="undefined")for(d=0;d<c.aaSearchCols.length;d++)a.aoPreSearchCols[d]=

            {
                sSearch:decodeURIComponent(c.aaSearchCols[d][0]),
                bRegex:!c.aaSearchCols[d][1]
                };
                
            if(typeof c.abVisCols!="undefined"){
                b.saved_aoColumns=[];
                for(d=0;d<c.abVisCols.length;d++){
                    b.saved_aoColumns[d]={
                    };
                    
                    b.saved_aoColumns[d].bVisible=c.abVisCols[d]
                    }
                }
            }
}
}
function Sa(a,b,c,d,f){
    var e=new Date;
    e.setTime(e.getTime()+c*1E3);
    c=wa.location.pathname.split("/");
    a=a+"_"+c.pop().replace(/[\/:]/g,"").toLowerCase();
    var h;
    if(f!==null){
        h=typeof i.parseJSON=="function"?i.parseJSON(b):eval("("+b+")");
        b=f(a,h,e.toGMTString(),
            c.join("/")+"/")
        }else b=a+"="+encodeURIComponent(b)+"; expires="+e.toGMTString()+"; path="+c.join("/")+"/";
    f="";
    e=9999999999999;
    if((ta(a)!==null?p.cookie.length:b.length+p.cookie.length)+10>4096){
        a=p.cookie.split(";");
        for(var j=0,k=a.length;j<k;j++)if(a[j].indexOf(d)!=-1){
            var m=a[j].split("=");
            try{
                h=eval("("+decodeURIComponent(m[1])+")")
                }catch(t){
                continue
            }
            if(typeof h.iCreate!="undefined"&&h.iCreate<e){
                f=m[0];
                e=h.iCreate
                }
            }
        if(f!=="")p.cookie=f+"=; expires=Thu, 01-Jan-1970 00:00:01 GMT; path="+c.join("/")+
        "/"
        }
        p.cookie=b
}
function ta(a){
    var b=wa.location.pathname.split("/");
    a=a+"_"+b[b.length-1].replace(/[\/:]/g,"").toLowerCase()+"=";
    b=p.cookie.split(";");
    for(var c=0;c<b.length;c++){
        for(var d=b[c];d.charAt(0)==" ";)d=d.substring(1,d.length);
        if(d.indexOf(a)===0)return decodeURIComponent(d.substring(a.length,d.length))
            }
            return null
    }
    function W(a,b){
    b=b.getElementsByTagName("tr");
    var c,d,f,e,h,j,k,m,t=function(O,B,F){
        for(;typeof O[B][F]!="undefined";)F++;
        return F
        };
        
    a.splice(0,a.length);
    d=0;
    for(j=b.length;d<
        j;d++)a.push([]);
    d=0;
    for(j=b.length;d<j;d++){
        f=0;
        for(k=b[d].childNodes.length;f<k;f++){
            c=b[d].childNodes[f];
            if(c.nodeName.toUpperCase()=="TD"||c.nodeName.toUpperCase()=="TH"){
                var q=c.getAttribute("colspan")*1,I=c.getAttribute("rowspan")*1;
                q=!q||q===0||q===1?1:q;
                I=!I||I===0||I===1?1:I;
                m=t(a,d,0);
                for(h=0;h<q;h++)for(e=0;e<I;e++){
                    a[d+e][m+h]={
                        cell:c,
                        unique:q==1?true:false
                        };
                        
                    a[d+e].nTr=b[d]
                    }
                }
                }
    }
}
function S(a,b,c){
    var d=[];
    if(typeof c=="undefined"){
        c=a.aoHeader;
        if(typeof b!="undefined"){
            c=[];
            W(c,b)
            }
        }
    b=0;
for(var f=c.length;b<f;b++)for(var e=0,h=c[b].length;e<h;e++)if(c[b][e].unique&&(typeof d[e]=="undefined"||!a.bSortCellsTop))d[e]=c[b][e].cell;return d
}
function Ua(){
    var a=p.createElement("p"),b=a.style;
    b.width="100%";
    b.height="200px";
    var c=p.createElement("div");
    b=c.style;
    b.position="absolute";
    b.top="0px";
    b.left="0px";
    b.visibility="hidden";
    b.width="200px";
    b.height="150px";
    b.overflow="hidden";
    c.appendChild(a);
    p.body.appendChild(c);
    b=a.offsetWidth;
    c.style.overflow="scroll";
    a=a.offsetWidth;
    if(b==a)a=
        c.clientWidth;
    p.body.removeChild(c);
    return b-a
    }
    function P(a,b,c){
    for(var d=0,f=b.length;d<f;d++)for(var e=0,h=b[d].childNodes.length;e<h;e++)if(b[d].childNodes[e].nodeType==1)typeof c!="undefined"?a(b[d].childNodes[e],c[d].childNodes[e]):a(b[d].childNodes[e])
        }
        function n(a,b,c,d){
    if(typeof d=="undefined")d=c;
    if(typeof b[c]!="undefined")a[d]=b[c]
        }
        function da(a,b,c){
    for(var d=[],f=0,e=a.aoColumns.length;f<e;f++)d.push(H(a,b,f,c));
    return d
    }
    function H(a,b,c,d){
    var f=a.aoColumns[c];
    if((c=f.fnGetData(a.aoData[b]._aData))===
        undefined){
        if(a.iDrawError!=a.iDraw&&f.sDefaultContent===null){
            J(a,0,"Requested unknown parameter '"+f.mDataProp+"' from the data source for row "+b);
            a.iDrawError=a.iDraw
            }
            return f.sDefaultContent
        }
        if(c===null&&f.sDefaultContent!==null)c=f.sDefaultContent;
    if(d=="display"&&c===null)return"";
    return c
    }
    function N(a,b,c,d){
    a.aoColumns[c].fnSetData(a.aoData[b]._aData,d)
    }
    function Z(a){
    if(a===null)return function(){
        return null
        };
    else if(typeof a=="function")return function(c){
        return a(c)
        };
    else if(typeof a==
        "string"&&a.indexOf(".")!=-1){
        var b=a.split(".");
        return b.length==2?function(c){
            return c[b[0]][b[1]]
            }:b.length==3?function(c){
            return c[b[0]][b[1]][b[2]]
            }:function(c){
            for(var d=0,f=b.length;d<f;d++)c=c[b[d]];
            return c
            }
        }else return function(c){
    return c[a]
    }
}
function ya(a){
    if(a===null)return function(){
        };
    else if(typeof a=="function")return function(c,d){
        return a(c,d)
        };
    else if(typeof a=="string"&&a.indexOf(".")!=-1){
        var b=a.split(".");
        return b.length==2?function(c,d){
            c[b[0]][b[1]]=d
            }:b.length==3?function(c,
            d){
            c[b[0]][b[1]][b[2]]=d
            }:function(c,d){
            for(var f=0,e=b.length-1;f<e;f++)c=c[b[f]];
            c[b[b.length-1]]=d
            }
        }else return function(c,d){
    c[a]=d
    }
}
this.oApi={
};

this.fnDraw=function(a){
    var b=A(this[o.iApiIndex]);
    if(typeof a!="undefined"&&a===false){
        E(b);
        C(b)
        }else ba(b)
        };
        
this.fnFilter=function(a,b,c,d,f){
    var e=A(this[o.iApiIndex]);
    if(e.oFeatures.bFilter){
        if(typeof c=="undefined")c=false;
        if(typeof d=="undefined")d=true;
        if(typeof f=="undefined")f=true;
        if(typeof b=="undefined"||b===null){
            M(e,{
                sSearch:a,
                bRegex:c,
                bSmart:d
            },1);
            if(f&&typeof e.aanFeatures.f!="undefined"){
                b=e.aanFeatures.f;
                c=0;
                for(d=b.length;c<d;c++)i("input",b[c]).val(a)
                    }
                }else{
        e.aoPreSearchCols[b].sSearch=a;
        e.aoPreSearchCols[b].bRegex=c;
        e.aoPreSearchCols[b].bSmart=d;
        M(e,e.oPreviousSearch,1)
        }
    }
};

this.fnSettings=function(){
    return A(this[o.iApiIndex])
    };
    
this.fnVersionCheck=o.fnVersionCheck;
this.fnSort=function(a){
    var b=A(this[o.iApiIndex]);
    b.aaSorting=a;
    R(b)
    };
    
this.fnSortListener=function(a,b,c){
    ga(A(this[o.iApiIndex]),a,b,c)
    };
    
this.fnAddData=function(a,
    b){
    if(a.length===0)return[];
    var c=[],d,f=A(this[o.iApiIndex]);
    if(typeof a[0]=="object")for(var e=0;e<a.length;e++){
        d=v(f,a[e]);
        if(d==-1)return c;
        c.push(d)
        }else{
        d=v(f,a);
        if(d==-1)return c;
        c.push(d)
        }
        f.aiDisplay=f.aiDisplayMaster.slice();
    if(typeof b=="undefined"||b)ba(f);
    return c
    };
    
this.fnDeleteRow=function(a,b,c){
    var d=A(this[o.iApiIndex]);
    a=typeof a=="object"?U(d,a):a;
    var f=d.aoData.splice(a,1),e=i.inArray(a,d.aiDisplay);
    d.asDataSearch.splice(e,1);
    ra(d.aiDisplayMaster,a);
    ra(d.aiDisplay,a);
    typeof b==
    "function"&&b.call(this,d,f);
    if(d._iDisplayStart>=d.aiDisplay.length){
        d._iDisplayStart-=d._iDisplayLength;
        if(d._iDisplayStart<0)d._iDisplayStart=0
            }
            if(typeof c=="undefined"||c){
        E(d);
        C(d)
        }
        return f
    };
    
this.fnClearTable=function(a){
    var b=A(this[o.iApiIndex]);
    ia(b);
    if(typeof a=="undefined"||a)C(b)
        };
        
this.fnOpen=function(a,b,c){
    var d=A(this[o.iApiIndex]);
    this.fnClose(a);
    var f=p.createElement("tr"),e=p.createElement("td");
    f.appendChild(e);
    e.className=c;
    e.colSpan=X(d);
    if(typeof b.jquery!="undefined"||typeof b==
        "object")e.appendChild(b);else e.innerHTML=b;
    b=i("tr",d.nTBody);
    i.inArray(a,b)!=-1&&i(f).insertAfter(a);
    d.aoOpenRows.push({
        nTr:f,
        nParent:a
    });
    return f
    };
    
this.fnClose=function(a){
    for(var b=A(this[o.iApiIndex]),c=0;c<b.aoOpenRows.length;c++)if(b.aoOpenRows[c].nParent==a){
        (a=b.aoOpenRows[c].nTr.parentNode)&&a.removeChild(b.aoOpenRows[c].nTr);
        b.aoOpenRows.splice(c,1);
        return 0
        }
        return 1
    };
    
this.fnGetData=function(a,b){
    var c=A(this[o.iApiIndex]);
    if(typeof a!="undefined"){
        a=typeof a=="object"?U(c,a):a;
        if(typeof b!=
            "undefined")return H(c,a,b,"");
        return typeof c.aoData[a]!="undefined"?c.aoData[a]._aData:null
        }
        return aa(c)
    };
    
this.fnGetNodes=function(a){
    var b=A(this[o.iApiIndex]);
    if(typeof a!="undefined")return typeof b.aoData[a]!="undefined"?b.aoData[a].nTr:null;
    return $(b)
    };
    
this.fnGetPosition=function(a){
    var b=A(this[o.iApiIndex]),c=a.nodeName.toUpperCase();
    if(c=="TR")return U(b,a);
    else if(c=="TD"||c=="TH"){
        c=U(b,a.parentNode);
        for(var d=Q(b,c),f=0;f<b.aoColumns.length;f++)if(d[f]==a)return[c,pa(b,f),f]
            }
            return null
    };
this.fnUpdate=function(a,b,c,d,f){
    var e=A(this[o.iApiIndex]);
    b=typeof b=="object"?U(e,b):b;
    if(i.isArray(a)&&typeof a=="object"){
        e.aoData[b]._aData=a.slice();
        for(c=0;c<e.aoColumns.length;c++)this.fnUpdate(H(e,b,c),b,c,false,false)
            }else if(typeof a=="object"){
        e.aoData[b]._aData=i.extend(true,{
            },a);
        for(c=0;c<e.aoColumns.length;c++)this.fnUpdate(H(e,b,c),b,c,false,false)
            }else{
        a=a;
        N(e,b,c,a);
        if(e.aoColumns[c].fnRender!==null){
            a=e.aoColumns[c].fnRender({
                iDataRow:b,
                iDataColumn:c,
                aData:e.aoData[b]._aData,
                oSettings:e
            });
            e.aoColumns[c].bUseRendered&&N(e,b,c,a)
            }
            if(e.aoData[b].nTr!==null)Q(e,b)[c].innerHTML=a
            }
            c=i.inArray(b,e.aiDisplay);
    e.asDataSearch[c]=na(e,da(e,b,"filter"));
    if(typeof f=="undefined"||f)ca(e);
    if(typeof d=="undefined"||d)ba(e);
    return 0
    };
    
this.fnSetColumnVis=function(a,b,c){
    var d=A(this[o.iApiIndex]),f,e;
    e=d.aoColumns.length;
    var h,j;
    if(d.aoColumns[a].bVisible!=b){
        if(b){
            for(f=j=0;f<a;f++)d.aoColumns[f].bVisible&&j++;
            j=j>=X(d);
            if(!j)for(f=a;f<e;f++)if(d.aoColumns[f].bVisible){
                h=f;
                break
            }
            f=0;
            for(e=d.aoData.length;f<e;f++)if(d.aoData[f].nTr!==null)j?d.aoData[f].nTr.appendChild(d.aoData[f]._anHidden[a]):d.aoData[f].nTr.insertBefore(d.aoData[f]._anHidden[a],Q(d,f)[h])
                }else{
            f=0;
            for(e=d.aoData.length;f<e;f++)if(d.aoData[f].nTr!==null){
                h=Q(d,f)[a];
                d.aoData[f]._anHidden[a]=h;
                h.parentNode.removeChild(h)
                }
            }
            d.aoColumns[a].bVisible=b;
    L(d,d.aoHeader);
    d.nTFoot&&L(d,d.aoFooter);
    f=0;
    for(e=d.aoOpenRows.length;f<e;f++)d.aoOpenRows[f].nTr.colSpan=X(d);
    if(typeof c=="undefined"||c){
        ca(d);
        C(d)
        }
        sa(d)
    }
};

this.fnPageChange=
function(a,b){
    var c=A(this[o.iApiIndex]);
    ja(c,a);
    E(c);
    if(typeof b=="undefined"||b)C(c)
        };
        
this.fnDestroy=function(){
    var a=A(this[o.iApiIndex]),b=a.nTableWrapper.parentNode,c=a.nTBody,d,f;
    a.bDestroying=true;
    d=0;
    for(f=a.aoColumns.length;d<f;d++)a.aoColumns[d].bVisible===false&&this.fnSetColumnVis(d,true);
    i(a.nTableWrapper).find("*").andSelf().unbind(".DT");
    i("tbody>tr>td."+a.oClasses.sRowEmpty,a.nTable).parent().remove();
    if(a.nTable!=a.nTHead.parentNode){
        i(">thead",a.nTable).remove();
        a.nTable.appendChild(a.nTHead)
        }
        if(a.nTFoot&&
        a.nTable!=a.nTFoot.parentNode){
        i(">tfoot",a.nTable).remove();
        a.nTable.appendChild(a.nTFoot)
        }
        a.nTable.parentNode.removeChild(a.nTable);
    i(a.nTableWrapper).remove();
    a.aaSorting=[];
    a.aaSortingFixed=[];
    T(a);
    i($(a)).removeClass(a.asStripClasses.join(" "));
    if(a.bJUI){
        i("th",a.nTHead).removeClass([o.oStdClasses.sSortable,o.oJUIClasses.sSortableAsc,o.oJUIClasses.sSortableDesc,o.oJUIClasses.sSortableNone].join(" "));
        i("th span."+o.oJUIClasses.sSortIcon,a.nTHead).remove();
        i("th",a.nTHead).each(function(){
            var e=
            i("div."+o.oJUIClasses.sSortJUIWrapper,this),h=e.contents();
            i(this).append(h);
            e.remove()
            })
        }else i("th",a.nTHead).removeClass([o.oStdClasses.sSortable,o.oStdClasses.sSortableAsc,o.oStdClasses.sSortableDesc,o.oStdClasses.sSortableNone].join(" "));
    a.nTableReinsertBefore?b.insertBefore(a.nTable,a.nTableReinsertBefore):b.appendChild(a.nTable);
    d=0;
    for(f=a.aoData.length;d<f;d++)a.aoData[d].nTr!==null&&c.appendChild(a.aoData[d].nTr);
    if(a.oFeatures.bAutoWidth===true)a.nTable.style.width=u(a.sDestroyWidth);
    i(">tr:even",c).addClass(a.asDestoryStrips[0]);
    i(">tr:odd",c).addClass(a.asDestoryStrips[1]);
    d=0;
    for(f=D.length;d<f;d++)D[d]==a&&D.splice(d,1);
    a=null
    };
    
this.fnAdjustColumnSizing=function(a){
    var b=A(this[o.iApiIndex]);
    ca(b);
    if(typeof a=="undefined"||a)this.fnDraw(false);
    else if(b.oScroll.sX!==""||b.oScroll.sY!=="")this.oApi._fnScrollDraw(b)
        };
        
for(var ua in o.oApi)if(ua)this[ua]=r(ua);this.oApi._fnExternApiFunc=r;
this.oApi._fnInitalise=s;
this.oApi._fnInitComplete=w;
this.oApi._fnLanguageProcess=y;
this.oApi._fnAddColumn=
G;
this.oApi._fnColumnOptions=x;
this.oApi._fnAddData=v;
this.oApi._fnCreateTr=z;
this.oApi._fnGatherData=Y;
this.oApi._fnBuildHead=V;
this.oApi._fnDrawHead=L;
this.oApi._fnDraw=C;
this.oApi._fnReDraw=ba;
this.oApi._fnAjaxUpdate=za;
this.oApi._fnAjaxUpdateDraw=Aa;
this.oApi._fnAddOptionsHtml=xa;
this.oApi._fnFeatureHtmlTable=Fa;
this.oApi._fnScrollDraw=Ia;
this.oApi._fnAjustColumnSizing=ca;
this.oApi._fnFeatureHtmlFilter=Da;
this.oApi._fnFilterComplete=M;
this.oApi._fnFilterCustom=Ma;
this.oApi._fnFilterColumn=La;
this.oApi._fnFilter=Ka;
this.oApi._fnBuildSearchArray=ka;
this.oApi._fnBuildSearchRow=na;
this.oApi._fnFilterCreateSearch=la;
this.oApi._fnDataToSearch=ma;
this.oApi._fnSort=R;
this.oApi._fnSortAttachListener=ga;
this.oApi._fnSortingClasses=T;
this.oApi._fnFeatureHtmlPaginate=Ha;
this.oApi._fnPageChange=ja;
this.oApi._fnFeatureHtmlInfo=Ga;
this.oApi._fnUpdateInfo=Na;
this.oApi._fnFeatureHtmlLength=Ca;
this.oApi._fnFeatureHtmlProcessing=Ea;
this.oApi._fnProcessingDisplay=K;
this.oApi._fnVisibleToColumnIndex=Ja;
this.oApi._fnColumnIndexToVisible=
pa;
this.oApi._fnNodeToDataIndex=U;
this.oApi._fnVisbleColumns=X;
this.oApi._fnCalculateEnd=E;
this.oApi._fnConvertToWidth=Oa;
this.oApi._fnCalculateColumnWidths=ea;
this.oApi._fnScrollingWidthAdjust=Qa;
this.oApi._fnGetWidestNode=Pa;
this.oApi._fnGetMaxLenString=Ra;
this.oApi._fnStringToCss=u;
this.oApi._fnArrayCmp=Va;
this.oApi._fnDetectType=fa;
this.oApi._fnSettingsFromNode=A;
this.oApi._fnGetDataMaster=aa;
this.oApi._fnGetTrNodes=$;
this.oApi._fnGetTdNodes=Q;
this.oApi._fnEscapeRegex=oa;
this.oApi._fnDeleteIndex=
ra;
this.oApi._fnReOrderIndex=Ba;
this.oApi._fnColumnOrdering=ha;
this.oApi._fnLog=J;
this.oApi._fnClearTable=ia;
this.oApi._fnSaveState=sa;
this.oApi._fnLoadState=Ta;
this.oApi._fnCreateCookie=Sa;
this.oApi._fnReadCookie=ta;
this.oApi._fnDetectHeader=W;
this.oApi._fnGetUniqueThs=S;
this.oApi._fnScrollBarWidth=Ua;
this.oApi._fnApplyToChildren=P;
this.oApi._fnMap=n;
this.oApi._fnGetRowData=da;
this.oApi._fnGetCellData=H;
this.oApi._fnSetCellData=N;
this.oApi._fnGetObjectDataFn=Z;
this.oApi._fnSetObjectDataFn=ya;
var va=
this;
return this.each(function(){
    var a=0,b,c,d,f;
    a=0;
    for(b=D.length;a<b;a++){
        if(D[a].nTable==this)if(typeof g=="undefined"||typeof g.bRetrieve!="undefined"&&g.bRetrieve===true)return D[a].oInstance;
            else if(typeof g.bDestroy!="undefined"&&g.bDestroy===true){
            D[a].oInstance.fnDestroy();
            break
        }else{
            J(D[a],0,"Cannot reinitialise DataTable.\n\nTo retrieve the DataTables object for this table, please pass either no arguments to the dataTable() function, or set bRetrieve to true. Alternatively, to destory the old table and create a new one, set bDestroy to true (note that a lot of changes to the configuration can be made through the API which is usually much faster).");
            return
        }
        if(D[a].sTableId!==""&&D[a].sTableId==this.getAttribute("id")){
            D.splice(a,1);
            break
        }
    }
    var e=new l;
D.push(e);
    var h=false,j=false;
    a=this.getAttribute("id");
    if(a!==null){
    e.sTableId=a;
    e.sInstance=a
    }else e.sInstance=o._oExternConfig.iNextUnique++;
    if(this.nodeName.toLowerCase()!="table")J(e,0,"Attempted to initialise DataTables on a node which is not a table: "+this.nodeName);
    else{
    e.nTable=this;
    e.oInstance=va.length==1?va:i(this).dataTable();
    e.oApi=va.oApi;
    e.sDestroyWidth=i(this).width();
    if(typeof g!=
        "undefined"&&g!==null){
        e.oInit=g;
        n(e.oFeatures,g,"bPaginate");
        n(e.oFeatures,g,"bLengthChange");
        n(e.oFeatures,g,"bFilter");
        n(e.oFeatures,g,"bSort");
        n(e.oFeatures,g,"bInfo");
        n(e.oFeatures,g,"bProcessing");
        n(e.oFeatures,g,"bAutoWidth");
        n(e.oFeatures,g,"bSortClasses");
        n(e.oFeatures,g,"bServerSide");
        n(e.oFeatures,g,"bDeferRender");
        n(e.oScroll,g,"sScrollX","sX");
        n(e.oScroll,g,"sScrollXInner","sXInner");
        n(e.oScroll,g,"sScrollY","sY");
        n(e.oScroll,g,"bScrollCollapse","bCollapse");
        n(e.oScroll,g,"bScrollInfinite",
            "bInfinite");
        n(e.oScroll,g,"iScrollLoadGap","iLoadGap");
        n(e.oScroll,g,"bScrollAutoCss","bAutoCss");
        n(e,g,"asStripClasses");
        n(e,g,"fnPreDrawCallback");
        n(e,g,"fnRowCallback");
        n(e,g,"fnHeaderCallback");
        n(e,g,"fnFooterCallback");
        n(e,g,"fnCookieCallback");
        n(e,g,"fnInitComplete");
        n(e,g,"fnServerData");
        n(e,g,"fnFormatNumber");
        n(e,g,"aaSorting");
        n(e,g,"aaSortingFixed");
        n(e,g,"aLengthMenu");
        n(e,g,"sPaginationType");
        n(e,g,"sAjaxSource");
        n(e,g,"sAjaxDataProp");
        n(e,g,"iCookieDuration");
        n(e,g,"sCookiePrefix");
        n(e,g,"sDom");
        n(e,g,"bSortCellsTop");
        n(e,g,"oSearch","oPreviousSearch");
        n(e,g,"aoSearchCols","aoPreSearchCols");
        n(e,g,"iDisplayLength","_iDisplayLength");
        n(e,g,"bJQueryUI","bJUI");
        n(e.oLanguage,g,"fnInfoCallback");
        typeof g.fnDrawCallback=="function"&&e.aoDrawCallback.push({
            fn:g.fnDrawCallback,
            sName:"user"
        });
        typeof g.fnStateSaveCallback=="function"&&e.aoStateSave.push({
            fn:g.fnStateSaveCallback,
            sName:"user"
        });
        typeof g.fnStateLoadCallback=="function"&&e.aoStateLoad.push({
            fn:g.fnStateLoadCallback,
            sName:"user"
        });
        if(e.oFeatures.bServerSide&&e.oFeatures.bSort&&e.oFeatures.bSortClasses)e.aoDrawCallback.push({
            fn:T,
            sName:"server_side_sort_classes"
        });else e.oFeatures.bDeferRender&&e.aoDrawCallback.push({
            fn:T,
            sName:"defer_sort_classes"
        });
        if(typeof g.bJQueryUI!="undefined"&&g.bJQueryUI){
            e.oClasses=o.oJUIClasses;
            if(typeof g.sDom=="undefined")e.sDom='<"H"lfr>t<"F"ip>'
                }
                if(e.oScroll.sX!==""||e.oScroll.sY!=="")e.oScroll.iBarWidth=Ua();
        if(typeof g.iDisplayStart!="undefined"&&typeof e.iInitDisplayStart=="undefined"){
            e.iInitDisplayStart=
            g.iDisplayStart;
            e._iDisplayStart=g.iDisplayStart
            }
            if(typeof g.bStateSave!="undefined"){
            e.oFeatures.bStateSave=g.bStateSave;
            Ta(e,g);
            e.aoDrawCallback.push({
                fn:sa,
                sName:"state_save"
            })
            }
            if(typeof g.iDeferLoading!="undefined"){
            e.bDeferLoading=true;
            e._iRecordsTotal=g.iDeferLoading;
            e._iRecordsDisplay=g.iDeferLoading
            }
            if(typeof g.aaData!="undefined")j=true;
        if(typeof g!="undefined"&&typeof g.aoData!="undefined")g.aoColumns=g.aoData;
        if(typeof g.oLanguage!="undefined")if(typeof g.oLanguage.sUrl!="undefined"&&g.oLanguage.sUrl!==
            ""){
            e.oLanguage.sUrl=g.oLanguage.sUrl;
            i.getJSON(e.oLanguage.sUrl,null,function(t){
                y(e,t,true)
                });
            h=true
            }else y(e,g.oLanguage,false)
            }else g={
        };
        
    if(typeof g.asStripClasses=="undefined"){
        e.asStripClasses.push(e.oClasses.sStripOdd);
        e.asStripClasses.push(e.oClasses.sStripEven)
        }
        c=false;
    d=i(">tbody>tr",this);
    a=0;
    for(b=e.asStripClasses.length;a<b;a++)if(d.filter(":lt(2)").hasClass(e.asStripClasses[a])){
        c=true;
        break
    }
    if(c){
        e.asDestoryStrips=["",""];
        if(i(d[0]).hasClass(e.oClasses.sStripOdd))e.asDestoryStrips[0]+=
            e.oClasses.sStripOdd+" ";
        if(i(d[0]).hasClass(e.oClasses.sStripEven))e.asDestoryStrips[0]+=e.oClasses.sStripEven;
        if(i(d[1]).hasClass(e.oClasses.sStripOdd))e.asDestoryStrips[1]+=e.oClasses.sStripOdd+" ";
        if(i(d[1]).hasClass(e.oClasses.sStripEven))e.asDestoryStrips[1]+=e.oClasses.sStripEven;
        d.removeClass(e.asStripClasses.join(" "))
        }
        c=[];
    var k;
    a=this.getElementsByTagName("thead");
    if(a.length!==0){
        W(e.aoHeader,a[0]);
        c=S(e)
        }
        if(typeof g.aoColumns=="undefined"){
        k=[];
        a=0;
        for(b=c.length;a<b;a++)k.push(null)
            }else k=
        g.aoColumns;
    a=0;
    for(b=k.length;a<b;a++){
        if(typeof g.saved_aoColumns!="undefined"&&g.saved_aoColumns.length==b){
            if(k[a]===null)k[a]={
                };
                
            k[a].bVisible=g.saved_aoColumns[a].bVisible
            }
            G(e,c?c[a]:null)
        }
        if(typeof g.aoColumnDefs!="undefined")for(a=g.aoColumnDefs.length-1;a>=0;a--){
        var m=g.aoColumnDefs[a].aTargets;
        i.isArray(m)||J(e,1,"aTargets must be an array of targets, not a "+typeof m);
        c=0;
        for(d=m.length;c<d;c++)if(typeof m[c]=="number"&&m[c]>=0){
            for(;e.aoColumns.length<=m[c];)G(e);
            x(e,m[c],g.aoColumnDefs[a])
            }else if(typeof m[c]==
            "number"&&m[c]<0)x(e,e.aoColumns.length+m[c],g.aoColumnDefs[a]);
            else if(typeof m[c]=="string"){
            b=0;
            for(f=e.aoColumns.length;b<f;b++)if(m[c]=="_all"||i(e.aoColumns[b].nTh).hasClass(m[c]))x(e,b,g.aoColumnDefs[a])
                }
            }
        if(typeof k!="undefined"){
    a=0;
    for(b=k.length;a<b;a++)x(e,a,k[a])
        }
        a=0;
for(b=e.aaSorting.length;a<b;a++){
    if(e.aaSorting[a][0]>=e.aoColumns.length)e.aaSorting[a][0]=0;
    k=e.aoColumns[e.aaSorting[a][0]];
    if(typeof e.aaSorting[a][2]=="undefined")e.aaSorting[a][2]=0;
    if(typeof g.aaSorting=="undefined"&&
        typeof e.saved_aaSorting=="undefined")e.aaSorting[a][1]=k.asSorting[0];
    c=0;
    for(d=k.asSorting.length;c<d;c++)if(e.aaSorting[a][1]==k.asSorting[c]){
        e.aaSorting[a][2]=c;
        break
    }
    }
    T(e);
a=i(">thead",this);
if(a.length===0){
    a=[p.createElement("thead")];
    this.appendChild(a[0])
    }
    e.nTHead=a[0];
a=i(">tbody",this);
if(a.length===0){
    a=[p.createElement("tbody")];
    this.appendChild(a[0])
    }
    e.nTBody=a[0];
a=i(">tfoot",this);
if(a.length>0){
    e.nTFoot=a[0];
    W(e.aoFooter,e.nTFoot)
    }
    if(j)for(a=0;a<g.aaData.length;a++)v(e,g.aaData[a]);
else Y(e);
e.aiDisplay=e.aiDisplayMaster.slice();
e.bInitialised=true;
h===false&&s(e)
}
})
}
})(jQuery,window,document);
(function(d){
    var a=location.href.replace(/#.*/,"");
    var c=d.localScroll=function(e){
        d("body").localScroll(e)
        };
        
    c.defaults={
        duration:1000,
        axis:"y",
        event:"click",
        stop:true,
        target:window,
        reset:true
    };
    
    c.hash=function(f){
        if(location.hash){
            f=d.extend({
                },c.defaults,f);
            f.hash=false;
            if(f.reset){
                var g=f.duration;
                delete f.duration;
                d(f.target).scrollTo(0,f);
                f.duration=g
                }
                b(0,location,f)
            }
        };
    
d.fn.localScroll=function(e){
    e=d.extend({
        },c.defaults,e);
    return e.lazy?this.bind(e.event,function(g){
        var h=d([g.target,g.target.parentNode]).filter(f)[0];
        if(h){
            b(g,h,e)
            }
        }):this.find("a,area").filter(f).bind(e.event,function(g){
    b(g,this,e)
    }).end().end();
    function f(){
    return !!this.href&&!!this.hash&&this.href.replace(this.hash,"")==a&&(!e.filter||d(this).is(e.filter))
    }
};

function b(i,p,g){
    var q=p.hash.slice(1),o=document.getElementById(q)||document.getElementsByName(q)[0];
    if(!o){
        return
    }
    if(i){
        i.preventDefault()
        }
        var n=d(g.target);
    if(g.lock&&n.is(":animated")||g.onBefore&&g.onBefore.call(g,i,o,n)===false){
        return
    }
    if(g.stop){
        n.stop(true)
        }
        if(g.hash){
        var m=o.id==q?"id":"name",l=d("<a> </a>").attr(m,q).css({
            position:"absolute",
            top:d(window).scrollTop(),
            left:d(window).scrollLeft()
            });
        o[m]="";
        d("body").prepend(l);
        location=p.hash;
        l.remove();
        o[m]=q
        }
        n.scrollTo(o,g).trigger("notify.serialScroll",[o])
    }
})(jQuery);
(function(a){
    a.jGrowl=function(b,c){
        if(a("#jGrowl").size()==0){
            a('<div id="jGrowl"></div>').addClass((c&&c.position)?c.position:a.jGrowl.defaults.position).appendTo("body")
            }
            a("#jGrowl").jGrowl(b,c)
        };
        
    a.fn.jGrowl=function(b,d){
        if(a.isFunction(this.each)){
            var c=arguments;
            return this.each(function(){
                var e=this;
                if(a(this).data("jGrowl.instance")==undefined){
                    a(this).data("jGrowl.instance",a.extend(new a.fn.jGrowl(),{
                        notifications:[],
                        element:null,
                        interval:null
                    }));
                    a(this).data("jGrowl.instance").startup(this)
                    }
                    if(a.isFunction(a(this).data("jGrowl.instance")[b])){
                    a(this).data("jGrowl.instance")[b].apply(a(this).data("jGrowl.instance"),a.makeArray(c).slice(1))
                    }else{
                    a(this).data("jGrowl.instance").create(b,d)
                    }
                })
        }
    };

a.extend(a.fn.jGrowl.prototype,{
    defaults:{
        pool:5,
        header:"",
        group:"",
        sticky:false,
        position:"top-right",
        glue:"after",
        theme:"default",
        themeState:"highlight",
        corners:"10px",
        check:250,
        life:3000,
        closeDuration:"normal",
        openDuration:"normal",
        easing:"swing",
        closer:true,
        closeTemplate:"&times;",
        closerTemplate:"<div>[ fechar todos ]</div>",
        log:function(c,b,d){
        },
        beforeOpen:function(c,b,d){
        },
        afterOpen:function(c,b,d){
        },
        open:function(c,b,d){
        },
        beforeClose:function(c,b,d){
        },
        close:function(c,b,d){
        },
        animateOpen:{
            opacity:"show"
        },
        animateClose:{
            opacity:"hide"
        }
    },
notifications:[],
element:null,
interval:null,
create:function(b,c){
    var c=a.extend({
        },this.defaults,c);
    if(typeof c.speed!=="undefined"){
        c.openDuration=c.speed;
        c.closeDuration=c.speed
        }
        this.notifications.push({
        message:b,
        options:c
    });
    c.log.apply(this.element,[this.element,b,c])
    },
render:function(d){
    var b=this;
    var c=d.message;
    var e=d.options;
    var d=a('<div class="jGrowl-notification '+e.themeState+" ui-corner-all"+((e.group!=undefined&&e.group!="")?" "+e.group:"")+'"><div class="jGrowl-close">'+e.closeTemplate+'</div><div class="jGrowl-header">'+e.header+'</div><div class="jGrowl-message">'+c+"</div></div>").data("jGrowl",e).addClass(e.theme).children("div.jGrowl-close").bind("click.jGrowl",function(){
        a(this).parent().trigger("jGrowl.close")
        }).parent();
    a(d).bind("mouseover.jGrowl",function(){
        a("div.jGrowl-notification",b.element).data("jGrowl.pause",true)
        }).bind("mouseout.jGrowl",function(){
        a("div.jGrowl-notification",b.element).data("jGrowl.pause",false)
        }).bind("jGrowl.beforeOpen",function(){
        if(e.beforeOpen.apply(d,[d,c,e,b.element])!=false){
            a(this).trigger("jGrowl.open")
            }
        }).bind("jGrowl.open",function(){
    if(e.open.apply(d,[d,c,e,b.element])!=false){
        if(e.glue=="after"){
            a("div.jGrowl-notification:last",b.element).after(d)
            }else{
            a("div.jGrowl-notification:first",b.element).before(d)
            }
            a(this).animate(e.animateOpen,e.openDuration,e.easing,function(){
            if(a.browser.msie&&(parseInt(a(this).css("opacity"),10)===1||parseInt(a(this).css("opacity"),10)===0)){
                this.style.removeAttribute("filter")
                }
                a(this).data("jGrowl").created=new Date();
            a(this).trigger("jGrowl.afterOpen")
            })
        }
    }).bind("jGrowl.afterOpen",function(){
    e.afterOpen.apply(d,[d,c,e,b.element])
    }).bind("jGrowl.beforeClose",function(){
    if(e.beforeClose.apply(d,[d,c,e,b.element])!=false){
        a(this).trigger("jGrowl.close")
        }
    }).bind("jGrowl.close",function(){
    a(this).data("jGrowl.pause",true);
    a(this).animate(e.animateClose,e.closeDuration,e.easing,function(){
        a(this).remove();
        var f=e.close.apply(d,[d,c,e,b.element]);
        if(a.isFunction(f)){
            f.apply(d,[d,c,e,b.element])
            }
        })
}).trigger("jGrowl.beforeOpen");
if(e.corners!=""&&a.fn.corner!=undefined){
    a(d).corner(e.corners)
    }
    if(a("div.jGrowl-notification:parent",b.element).size()>1&&a("div.jGrowl-closer",b.element).size()==0&&this.defaults.closer!=false){
    a(this.defaults.closerTemplate).addClass("jGrowl-closer ui-state-highlight ui-corner-all").addClass(this.defaults.theme).appendTo(b.element).animate(this.defaults.animateOpen,this.defaults.speed,this.defaults.easing).bind("click.jGrowl",function(){
        a(this).siblings().trigger("jGrowl.beforeClose");
        if(a.isFunction(b.defaults.closer)){
            b.defaults.closer.apply(a(this).parent()[0],[a(this).parent()[0]])
            }
        })
}
},
update:function(){
    a(this.element).find("div.jGrowl-notification:parent").each(function(){
        if(a(this).data("jGrowl")!=undefined&&a(this).data("jGrowl").created!=undefined&&(a(this).data("jGrowl").created.getTime()+parseInt(a(this).data("jGrowl").life))<(new Date()).getTime()&&a(this).data("jGrowl").sticky!=true&&(a(this).data("jGrowl.pause")==undefined||a(this).data("jGrowl.pause")!=true)){
            a(this).trigger("jGrowl.beforeClose")
        }
    });
if(this.notifications.length>0&&(this.defaults.pool==0||a(this.element).find("div.jGrowl-notification:parent").size()<this.defaults.pool)){
    this.render(this.notifications.shift())
    }
    if(a(this.element).find("div.jGrowl-notification:parent").size()<2){
    a(this.element).find("div.jGrowl-closer").animate(this.defaults.animateClose,this.defaults.speed,this.defaults.easing,function(){
        a(this).remove()
        })
    }
},
startup:function(b){
    this.element=a(b).addClass("jGrowl").append('<div class="jGrowl-notification"></div>');
    this.interval=setInterval(function(){
        a(b).data("jGrowl.instance").update()
        },parseInt(this.defaults.check));
    if(a.browser.msie&&parseInt(a.browser.version)<7&&!window.XMLHttpRequest){
        a(this.element).addClass("ie6")
        }
    },
shutdown:function(){
    a(this.element).removeClass("jGrowl").find("div.jGrowl-notification").remove();
    clearInterval(this.interval)
    },
close:function(){
    a(this.element).find("div.jGrowl-notification").each(function(){
        a(this).trigger("jGrowl.beforeClose")
        })
    }
});
a.jGrowl.defaults=a.fn.jGrowl.prototype.defaults
})(jQuery);
(function(c){
    var a=c.scrollTo=function(d,f,g){
        c(window).scrollTo(d,f,g)
        };
        
    a.defaults={
        axis:"xy",
        duration:parseFloat(c.fn.jquery)>=1.3?0:1
        };
        
    a.window=function(d){
        return c(window)._scrollable()
        };
        
    c.fn._scrollable=function(){
        return this.map(function(){
            var d=this,f=!d.nodeName||c.inArray(d.nodeName.toLowerCase(),["iframe","#document","html","body"])!=-1;
            if(!f){
                return d
                }
                var g=(d.contentWindow||d).document||d.ownerDocument||d;
            return c.browser.safari||g.compatMode=="BackCompat"?g.body:g.documentElement
            })
        };
        
    c.fn.scrollTo=function(f,e,d){
        if(typeof e=="object"){
            d=e;
            e=0
            }
            if(typeof d=="function"){
            d={
                onAfter:d
            }
        }
        if(f=="max"){
        f=9000000000
        }
        d=c.extend({
        },a.defaults,d);
    e=e||d.speed||d.duration;
    d.queue=d.queue&&d.axis.length>1;
    if(d.queue){
        e/=2
        }
        d.offset=b(d.offset);
    d.over=b(d.over);
    return this._scrollable().each(function(){
        var n=this,l=c(n),m=f,j,k={
        },h=l.is("html,body");
        switch(typeof m){
            case"number":case"string":
                if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(m)){
                m=b(m);
                break
            }
            m=c(m,this);
            case"object":
                if(m.is||m.style){
                j=(m=c(m)).offset()
                }
            }
            c.each(d.axis.split(""),function(q,r){
        var t=r=="x"?"Left":"Top",s=t.toLowerCase(),v="scroll"+t,p=n[v],g=a.max(n,r);
        if(j){
            k[v]=j[s]+(h?0:p-l.offset()[s]);
            if(d.margin){
                k[v]-=parseInt(m.css("margin"+t))||0;
                k[v]-=parseInt(m.css("border"+t+"Width"))||0
                }
                k[v]+=d.offset[s]||0;
            if(d.over[s]){
                k[v]+=m[r=="x"?"width":"height"]()*d.over[s]
                }
            }else{
        var u=m[s];
        k[v]=u.slice&&u.slice(-1)=="%"?parseFloat(u)/100*g:u
        }
        if(/^\d+$/.test(k[v])){
        k[v]=k[v]<=0?0:Math.min(k[v],g)
        }
        if(!q&&d.queue){
        if(p!=k[v]){
            i(d.onAfterFirst)
            }
            delete k[v]
    }
    });
i(d.onAfter);
    function i(g){
    l.animate(k,e,d.easing,g&&function(){
        g.call(this,f,d)
        })
    }
}).end()
};

a.max=function(g,j){
    var n=j=="x"?"Width":"Height",k="scroll"+n;
    if(!c(g).is("html,body")){
        return g[k]-c(g)[n.toLowerCase()]()
        }
        var o="client"+n,f=g.ownerDocument.documentElement,d=g.ownerDocument.body;
    return Math.max(f[k],d[k])-Math.min(f[o],d[o])
    };
    
function b(d){
    return typeof d=="object"?d:{
        top:d,
        left:d
    }
}
})(jQuery);
$.fn.sliderNav=function(b){
    var f={
        items:["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"],
        debug:false,
        height:null,
        arrows:true
    };
    
    var e=$.extend(f,b);
    var g=$.meta?$.extend({
        },e,$$.data()):e;
    var d=$(this);
    $(d).addClass("slider");
    $(".slider-content li:first",d).addClass("selected");
    $(d).append('<div class="slider-nav"><ul></ul></div>');
    for(var c in g.items){
        $(".slider-nav ul",d).append("<li><a alt='#"+g.items[c]+"'>"+g.items[c]+"</a></li>")
        }
        var a=$(".slider-nav",d).height();
    if(g.height){
        a=g.height
        }
        $(".slider-content, .slider-nav",d).css("height",a);
    if(g.debug){
        $(d).append('<div id="debug">Scroll Offset: <span>0</span></div>')
        }
        $(".slider-nav a",d).mouseover(function(k){
        var m=$(this).attr("alt");
        var i=$(".slider-content",d).offset().top;
        var j=$(".slider-content "+m,d).offset().top;
        var h=$(".slider-nav",d).height();
        if(g.height){
            h=g.height
            }
            var l=(j-i)-h/8;
        $(".slider-content li",d).removeClass("selected");
        $(m).addClass("selected");
        $(".slider-content",d).stop().animate({
            scrollTop:"+="+l+"px"
            });
        if(g.debug){
            $("#debug span",d).html(j)
            }
        });
if(g.arrows){
    $(".slider-nav",d).css("top","20px");
    $(d).prepend('<div class="slide-up end"><span class="arrow up"></span></div>');
    $(d).append('<div class="slide-down"><span class="arrow down"></span></div>');
    $(".slide-down",d).click(function(){
        $(".slider-content",d).animate({
            scrollTop:"+="+a+"px"
            },500)
        });
    $(".slide-up",d).click(function(){
        $(".slider-content",d).animate({
            scrollTop:"-="+a+"px"
            },500)
        })
    }
};
/*
 *  SliderNav - A Simple Content Slider with a Navigation Bar
 *  Copyright 2010 Monjurul Dolon, http://mdolon.com/
 *  Released under the MIT, BSD, and GPL Licenses.
 *  More information: http://devgrow.com/slidernav
 */
$.fn.sliderNav=function(options){
    var defaults={
        items:["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"],
        debug:false,
        height:null,
        arrows:true
    };
    
    var opts=$.extend(defaults,options);
    var o=$.meta?$.extend({
        },opts,$$.data()):opts;
    var slider=$(this);
    $(slider).addClass('slider');
    $('.slider-content li:first',slider).addClass('selected');
    $(slider).append('<div class="slider-nav"><ul></ul></div>');
    for(var i in o.items)$('.slider-nav ul',slider).append("<li><a alt='#"+o.items[i]+"'>"+o.items[i]+"</a></li>");var height=$('.slider-nav',slider).height();
    if(o.height)height=o.height;
    $('.slider-content, .slider-nav',slider).css('height',height);
    if(o.debug)$(slider).append('<div id="debug">Scroll Offset: <span>0</span></div>');
    $('.slider-nav a',slider).mouseover(function(event){
        var target=$(this).attr('alt');
        var cOffset=$('.slider-content',slider).offset().top;
        var tOffset=$('.slider-content '+target,slider).offset().top;
        var height=$('.slider-nav',slider).height();
        if(o.height)height=o.height;
        var pScroll=(tOffset-cOffset)-height/8;
        $('.slider-content li',slider).removeClass('selected');
        $(target).addClass('selected');
        $('.slider-content',slider).stop().animate({
            scrollTop:'+='+pScroll+'px'
            });
        if(o.debug)$('#debug span',slider).html(tOffset);
    });
    if(o.arrows){
        $('.slider-nav',slider).css('top','20px');
        $(slider).prepend('<div class="slide-up end"><span class="arrow up"></span></div>');
        $(slider).append('<div class="slide-down"><span class="arrow down"></span></div>');
        $('.slide-down',slider).click(function(){
            $('.slider-content',slider).animate({
                scrollTop:"+="+height+"px"
                },500);
        });
        $('.slide-up',slider).click(function(){
            $('.slider-content',slider).animate({
                scrollTop:"-="+height+"px"
                },500);
        });
    }
};
(function(c){
    function a(d){
        if(d.attr("title")||typeof(d.attr("original-title"))!="string"){
            d.attr("original-title",d.attr("title")||"").removeAttr("title")
            }
        }
    function b(e,d){
    this.$element=c(e);
    this.options=d;
    this.enabled=true;
    a(this.$element)
    }
    b.prototype={
    show:function(){
        var g=this.getTitle();
        if(g&&this.enabled){
            var f=this.tip();
            f.find(".tipsy-inner")[this.options.html?"html":"text"](g);
            f[0].className="tipsy";
            f.remove().css({
                top:0,
                left:0,
                visibility:"hidden",
                display:"block"
            }).appendTo(document.body);
            var j=c.extend({
                },this.$element.offset(),{
                    width:this.$element[0].offsetWidth,
                    height:this.$element[0].offsetHeight
                    });
            var d=f[0].offsetWidth,i=f[0].offsetHeight;
            var h=(typeof this.options.gravity=="function")?this.options.gravity.call(this.$element[0]):this.options.gravity;
            var e;
            switch(h.charAt(0)){
                case"n":
                    e={
                    top:j.top+j.height+this.options.offset,
                    left:j.left+j.width/2-d/2
                    };
                    
                break;
                case"s":
                    e={
                    top:j.top-i-this.options.offset,
                    left:j.left+j.width/2-d/2
                    };
                    
                break;
                case"e":
                    e={
                    top:j.top+j.height/2-i/2,
                    left:j.left-d-this.options.offset
                    };
                    
                break;
                case"w":
                    e={
                    top:j.top+j.height/2-i/2,
                    left:j.left+j.width+this.options.offset
                    };
                    
                break
                }
                if(h.length==2){
                if(h.charAt(1)=="w"){
                    e.left=j.left+j.width/2-15
                    }else{
                    e.left=j.left+j.width/2-d+15
                    }
                }
            f.css(e).addClass("tipsy-"+h);
        if(this.options.fade){
            f.stop().css({
                opacity:0,
                display:"block",
                visibility:"visible"
            }).animate({
                opacity:this.options.opacity
                })
            }else{
            f.css({
                visibility:"visible",
                opacity:this.options.opacity
                })
            }
        }
},
hide:function(){
    if(this.options.fade){
        this.tip().stop().fadeOut(function(){
            c(this).remove()
            })
        }else{
        this.tip().remove()
        }
    },
getTitle:function(){
    var f,d=this.$element,e=this.options;
    a(d);
    var f,e=this.options;
    if(typeof e.title=="string"){
        f=d.attr(e.title=="title"?"original-title":e.title)
        }else{
        if(typeof e.title=="function"){
            f=e.title.call(d[0])
            }
        }
    f=(""+f).replace(/(^\s*|\s*$)/,"");
return f||e.fallback
},
tip:function(){
    if(!this.$tip){
        this.$tip=c('<div class="tipsy"></div>').html('<div class="tipsy-arrow"></div><div class="tipsy-inner"/></div>')
        }
        return this.$tip
    },
validate:function(){
    if(!this.$element[0].parentNode){
        this.hide()
        }
    },
enable:function(){
    this.enabled=true
    },
disable:function(){
    this.enabled=false
    },
toggleEnabled:function(){
    this.enabled=!this.enabled
    }
};

c.fn.tipsy=function(h){
    if(h===true){
        return this.data("tipsy")
        }else{
        if(typeof h=="string"){
            return this.data("tipsy")[h]()
            }
        }
    h=c.extend({
    },c.fn.tipsy.defaults,h);
function g(k){
    var l=c.data(k,"tipsy");
    if(!l){
        l=new b(k,c.fn.tipsy.elementOptions(k,h));
        c.data(k,"tipsy",l)
        }
        return l
    }
    function j(){
    var k=g(this);
    k.hoverState="in";
    if(h.delayIn==0){
        k.show()
        }else{
        setTimeout(function(){
            if(k.hoverState=="in"){
                k.show()
                }
            },h.delayIn)
    }
}
function f(){
    var k=g(this);
    k.hoverState="out";
    if(h.delayOut==0){
        k.hide()
        }else{
        setTimeout(function(){
            if(k.hoverState=="out"){
                k.hide()
                }
            },h.delayOut)
    }
}
if(!h.live){
    this.each(function(){
        g(this)
        })
    }
    if(h.trigger!="manual"){
    var d=h.live?"live":"bind",i=h.trigger=="hover"?"mouseenter":"focus",e=h.trigger=="hover"?"mouseleave":"blur";
    this[d](i,j)[d](e,f)
    }
    return this
};

c.fn.tipsy.defaults={
    delayIn:0,
    delayOut:0,
    fade:false,
    fallback:"",
    gravity:"n",
    html:false,
    live:false,
    offset:0,
    opacity:0.8,
    title:"title",
    trigger:"hover"
};

c.fn.tipsy.elementOptions=function(e,d){
    return c.metadata?c.extend({
        },d,c(e).metadata()):d
    };
    
c.fn.tipsy.autoNS=function(){
    return c(this).offset().top>(c(document).scrollTop()+c(window).height()/2)?"s":"n"
    };
    
c.fn.tipsy.autoWE=function(){
    return c(this).offset().left>(c(document).scrollLeft()+c(window).width()/2)?"e":"w"
    }
})(jQuery);
(function(a){
    a.uniform={
        options:{
            selectClass:"selector",
            radioClass:"radio",
            checkboxClass:"checker",
            fileClass:"uploader",
            filenameClass:"filename",
            fileBtnClass:"action",
            fileDefaultText:"Nenhum arquivo selecionado",
            fileBtnText:"Selecione o arquivo",
            checkedClass:"checked",
            focusClass:"focus",
            disabledClass:"disabled",
            buttonClass:"uniform-button",
            activeClass:"active",
            hoverClass:"hover",
            useID:true,
            idPrefix:"uniform",
            resetSelector:false,
            autoHide:true
        },
        elements:[]
    };
    
    if(a.browser.msie&&a.browser.version<7){
        a.support.selectOpacity=false
        }else{
        a.support.selectOpacity=true
        }
        a.fn.uniform=function(k){
        k=a.extend(a.uniform.options,k);
        var d=this;
        if(k.resetSelector!=false){
            a(k.resetSelector).mouseup(function(){
                function l(){
                    a.uniform.update(d)
                    }
                    setTimeout(l,10)
                })
            }
            function j(l){
            $el=a(l);
            $el.addClass($el.attr("type"));
            b(l)
            }
            function g(l){
            a(l).addClass("uniform");
            b(l)
            }
            function i(o){
            var m=a(o);
            var p=a("<div>"),l=a("<span>");
            p.addClass(k.buttonClass);
            if(k.useID&&m.attr("id")!=""){
                p.attr("id",k.idPrefix+"-"+m.attr("id"))
                }
                var n;
            if(m.is("a")||m.is("button")){
                n=m.text()
                }else{
                if(m.is(":submit")||m.is(":reset")||m.is("input[type=button]")){
                    n=m.attr("value")
                    }
                }
            n=n==""?m.is(":reset")?"Reset":"Submit":n;
        l.html(n);
        m.css("opacity",0);
        m.wrap(p);
        m.wrap(l);
        p=m.closest("div");
        l=m.closest("span");
        if(m.is(":disabled")){
            p.addClass(k.disabledClass)
            }
            p.bind({
            "mouseenter.uniform":function(){
                p.addClass(k.hoverClass)
                },
            "mouseleave.uniform":function(){
                p.removeClass(k.hoverClass);
                p.removeClass(k.activeClass)
                },
            "mousedown.uniform touchbegin.uniform":function(){
                p.addClass(k.activeClass)
                },
            "mouseup.uniform touchend.uniform":function(){
                p.removeClass(k.activeClass)
                },
            "click.uniform touchend.uniform":function(r){
                if(a(r.target).is("span")||a(r.target).is("div")){
                    if(o[0].dispatchEvent){
                        var q=document.createEvent("MouseEvents");
                        q.initEvent("click",true,true);
                        o[0].dispatchEvent(q)
                        }else{
                        o[0].click()
                        }
                    }
            }
        });
o.bind({
    "focus.uniform":function(){
        p.addClass(k.focusClass)
        },
    "blur.uniform":function(){
        p.removeClass(k.focusClass)
        }
    });
a.uniform.noSelect(p);
b(o)
}
function e(o){
    var m=a(o);
    var p=a("<div />"),l=a("<span />");
    if(!m.css("display")=="none"&&k.autoHide){
        p.hide()
        }
        p.addClass(k.selectClass);
    if(k.useID&&o.attr("id")!=""){
        p.attr("id",k.idPrefix+"-"+o.attr("id"))
        }
        var n=o.find(":selected:first");
    if(n.length==0){
        n=o.find("option:first")
        }
        l.html(n.html());
    o.css("opacity",0);
    o.wrap(p);
    o.before(l);
    p=o.parent("div");
    l=o.siblings("span");
    o.bind({
        "change.uniform":function(){
            l.text(o.find(":selected").html());
            p.removeClass(k.activeClass)
            },
        "focus.uniform":function(){
            p.addClass(k.focusClass)
            },
        "blur.uniform":function(){
            p.removeClass(k.focusClass);
            p.removeClass(k.activeClass)
            },
        "mousedown.uniform touchbegin.uniform":function(){
            p.addClass(k.activeClass)
            },
        "mouseup.uniform touchend.uniform":function(){
            p.removeClass(k.activeClass)
            },
        "click.uniform touchend.uniform":function(){
            p.removeClass(k.activeClass)
            },
        "mouseenter.uniform":function(){
            p.addClass(k.hoverClass)
            },
        "mouseleave.uniform":function(){
            p.removeClass(k.hoverClass);
            p.removeClass(k.activeClass)
            },
        "keyup.uniform":function(){
            l.text(o.find(":selected").html())
            }
        });
if(a(o).attr("disabled")){
    p.addClass(k.disabledClass)
    }
    a.uniform.noSelect(l);
b(o)
}
function f(n){
    var m=a(n);
    var o=a("<div />"),l=a("<span />");
    if(!m.css("display")=="none"&&k.autoHide){
        o.hide()
        }
        o.addClass(k.checkboxClass);
    if(k.useID&&n.attr("id")!=""){
        o.attr("id",k.idPrefix+"-"+n.attr("id"))
        }
        a(n).wrap(o);
    a(n).wrap(l);
    l=n.parent();
    o=l.parent();
    a(n).css("opacity",0).bind({
        "focus.uniform":function(){
            o.addClass(k.focusClass)
            },
        "blur.uniform":function(){
            o.removeClass(k.focusClass)
            },
        "click.uniform touchend.uniform":function(){
            if(!a(n).attr("checked")){
                l.removeClass(k.checkedClass)
                }else{
                l.addClass(k.checkedClass)
                }
            },
    "mousedown.uniform touchbegin.uniform":function(){
        o.addClass(k.activeClass)
        },
    "mouseup.uniform touchend.uniform":function(){
        o.removeClass(k.activeClass)
        },
    "mouseenter.uniform":function(){
        o.addClass(k.hoverClass)
        },
    "mouseleave.uniform":function(){
        o.removeClass(k.hoverClass);
        o.removeClass(k.activeClass)
        }
    });
if(a(n).attr("checked")){
    l.addClass(k.checkedClass)
}
if(a(n).attr("disabled")){
    o.addClass(k.disabledClass)
    }
    b(n)
}
function c(n){
    var m=a(n);
    var o=a("<div />"),l=a("<span />");
    if(!m.css("display")=="none"&&k.autoHide){
        o.hide()
        }
        o.addClass(k.radioClass);
    if(k.useID&&n.attr("id")!=""){
        o.attr("id",k.idPrefix+"-"+n.attr("id"))
        }
        a(n).wrap(o);
    a(n).wrap(l);
    l=n.parent();
    o=l.parent();
    a(n).css("opacity",0).bind({
        "focus.uniform":function(){
            o.addClass(k.focusClass)
            },
        "blur.uniform":function(){
            o.removeClass(k.focusClass)
            },
        "click.uniform touchend.uniform":function(){
            if(!a(n).attr("checked")){
                l.removeClass(k.checkedClass)
                }else{
                var p=k.radioClass.split(" ")[0];
                a("."+p+" span."+k.checkedClass+":has([name='"+a(n).attr("name")+"'])").removeClass(k.checkedClass);
                l.addClass(k.checkedClass)
                }
            },
    "mousedown.uniform touchend.uniform":function(){
        if(!a(n).is(":disabled")){
            o.addClass(k.activeClass)
            }
        },
    "mouseup.uniform touchbegin.uniform":function(){
        o.removeClass(k.activeClass)
        },
    "mouseenter.uniform touchend.uniform":function(){
        o.addClass(k.hoverClass)
        },
    "mouseleave.uniform":function(){
        o.removeClass(k.hoverClass);
        o.removeClass(k.activeClass)
        }
    });
if(a(n).attr("checked")){
    l.addClass(k.checkedClass)
    }
    if(a(n).attr("disabled")){
    o.addClass(k.disabledClass)
    }
    b(n)
}
function h(q){
    var o=a(q);
    var r=a("<div />"),p=a("<span>"+k.fileDefaultText+"</span>"),m=a("<span>"+k.fileBtnText+"</span>");
    if(!o.css("display")=="none"&&k.autoHide){
        r.hide()
        }
        r.addClass(k.fileClass);
    p.addClass(k.filenameClass);
    m.addClass(k.fileBtnClass);
    if(k.useID&&o.attr("id")!=""){
        r.attr("id",k.idPrefix+"-"+o.attr("id"))
        }
        o.wrap(r);
    o.after(m);
    o.after(p);
    r=o.closest("div");
    p=o.siblings("."+k.filenameClass);
    m=o.siblings("."+k.fileBtnClass);
    if(!o.attr("size")){
        var l=r.width();
        o.attr("size",l/10)
        }
        var n=function(){
        var s=o.val();
        if(s===""){
            s=k.fileDefaultText
            }else{
            s=s.split(/[\/\\]+/);
            s=s[(s.length-1)]
            }
            p.text(s)
        };
        
    n();
    o.css("opacity",0).bind({
        "focus.uniform":function(){
            r.addClass(k.focusClass)
            },
        "blur.uniform":function(){
            r.removeClass(k.focusClass)
            },
        "mousedown.uniform":function(){
            if(!a(q).is(":disabled")){
                r.addClass(k.activeClass)
                }
            },
    "mouseup.uniform":function(){
        r.removeClass(k.activeClass)
        },
    "mouseenter.uniform":function(){
        r.addClass(k.hoverClass)
        },
    "mouseleave.uniform":function(){
        r.removeClass(k.hoverClass);
        r.removeClass(k.activeClass)
        }
    });
if(a.browser.msie){
    o.bind("click.uniform.ie7",function(){
        setTimeout(n,0)
        })
    }else{
    o.bind("change.uniform",n)
    }
    if(o.attr("disabled")){
    r.addClass(k.disabledClass)
    }
    a.uniform.noSelect(p);
a.uniform.noSelect(m);
b(q)
}
a.uniform.restore=function(l){
    if(l==undefined){
        l=a(a.uniform.elements)
        }
        a(l).each(function(){
        if(a(this).is(":checkbox")){
            a(this).unwrap().unwrap()
            }else{
            if(a(this).is("select")){
                a(this).siblings("span").remove();
                a(this).unwrap()
                }else{
                if(a(this).is(":radio")){
                    a(this).unwrap().unwrap()
                    }else{
                    if(a(this).is(":file")){
                        a(this).siblings("span").remove();
                        a(this).unwrap()
                        }else{
                        if(a(this).is("button, :submit, :reset, a, input[type='button']")){
                            a(this).unwrap().unwrap()
                            }
                        }
                }
        }
    }
a(this).unbind(".uniform");
a(this).css("opacity","1");
var m=a.inArray(a(l),a.uniform.elements);
a.uniform.elements.splice(m,1)
})
};

function b(l){
    l=a(l).get();
    if(l.length>1){
        a.each(l,function(m,n){
            a.uniform.elements.push(n)
            })
        }else{
        a.uniform.elements.push(l)
        }
    }
a.uniform.noSelect=function(l){
    function m(){
        return false
        }
        a(l).each(function(){
        this.onselectstart=this.ondragstart=m;
        a(this).mousedown(m).css({
            MozUserSelect:"none"
        })
        })
    };
    
a.uniform.update=function(l){
    if(l==undefined){
        l=a(a.uniform.elements)
        }
        l=a(l);
    l.each(function(){
        var n=a(this);
        if(n.is("select")){
            var m=n.siblings("span");
            var p=n.parent("div");
            p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);
            m.html(n.find(":selected").html());
            if(n.is(":disabled")){
                p.addClass(k.disabledClass)
                }else{
                p.removeClass(k.disabledClass)
                }
            }else{
        if(n.is(":checkbox")){
            var m=n.closest("span");
            var p=n.closest("div");
            p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);
            m.removeClass(k.checkedClass);
            if(n.is(":checked")){
                m.addClass(k.checkedClass)
                }
                if(n.is(":disabled")){
                p.addClass(k.disabledClass)
                }else{
                p.removeClass(k.disabledClass)
                }
            }else{
        if(n.is(":radio")){
            var m=n.closest("span");
            var p=n.closest("div");
            p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);
            m.removeClass(k.checkedClass);
            if(n.is(":checked")){
                m.addClass(k.checkedClass)
                }
                if(n.is(":disabled")){
                p.addClass(k.disabledClass)
                }else{
                p.removeClass(k.disabledClass)
                }
            }else{
        if(n.is(":file")){
            var p=n.parent("div");
            var o=n.siblings(k.filenameClass);
            btnTag=n.siblings(k.fileBtnClass);
            p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);
            o.text(n.val());
            if(n.is(":disabled")){
                p.addClass(k.disabledClass)
                }else{
                p.removeClass(k.disabledClass)
                }
            }else{
        if(n.is(":submit")||n.is(":reset")||n.is("button")||n.is("a")||l.is("input[type=button]")){
            var p=n.closest("div");
            p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);
            if(n.is(":disabled")){
                p.addClass(k.disabledClass)
                }else{
                p.removeClass(k.disabledClass)
                }
            }
    }
}
}
}
})
};

return this.each(function(){
    if(a.support.selectOpacity){
        var l=a(this);
        if(l.is("select")){
            if(l.attr("multiple")!=true){
                if(l.attr("size")==undefined||l.attr("size")<=1){
                    e(l)
                    }
                }
        }else{
    if(l.is(":checkbox")){
        f(l)
        }else{
        if(l.is(":radio")){
            c(l)
            }else{
            if(l.is(":file")){
                h(l)
                }else{
                if(l.is(":text, :password, input[type='email']")){
                    j(l)
                    }else{
                    if(l.is("textarea")){
                        g(l)
                        }else{
                        if(l.is("a")||l.is(":submit")||l.is(":reset")||l.is("button")||l.is("input[type=button]")){
                            i(l)
                            }
                        }
                }
        }
}
}
}
}
})
}
})(jQuery);
(function(a){
    a.uniform={
        options:{
            selectClass:"selector",
            radioClass:"radio",
            checkboxClass:"checker",
            fileClass:"uploader",
            filenameClass:"filename",
            fileBtnClass:"action",
            fileDefaultText:"Nenhum arquivo selecionado",
            fileBtnText:"Selecione o arquivo",
            checkedClass:"checked",
            focusClass:"focus",
            disabledClass:"disabled",
            buttonClass:"uniform-button",
            activeClass:"active",
            hoverClass:"hover",
            useID:true,
            idPrefix:"uniform",
            resetSelector:false,
            autoHide:true
        },
        elements:[]
    };
    
    if(a.browser.msie&&a.browser.version<7){
        a.support.selectOpacity=false
        }else{
        a.support.selectOpacity=true
        }
        a.fn.uniform=function(k){
        k=a.extend(a.uniform.options,k);
        var d=this;
        if(k.resetSelector!=false){
            a(k.resetSelector).mouseup(function(){
                function l(){
                    a.uniform.update(d)
                    }
                    setTimeout(l,10)
                })
            }
            function j(l){
            $el=a(l);
            $el.addClass($el.attr("type"));
            b(l)
            }
            function g(l){
            a(l).addClass("uniform");
            b(l)
            }
            function i(o){
            var m=a(o);
            var p=a("<div>"),l=a("<span>");
            p.addClass(k.buttonClass);
            if(k.useID&&m.attr("id")!=""){
                p.attr("id",k.idPrefix+"-"+m.attr("id"))
                }
                var n;
            if(m.is("a")||m.is("button")){
                n=m.text()
                }else{
                if(m.is(":submit")||m.is(":reset")||m.is("input[type=button]")){
                    n=m.attr("value")
                    }
                }
            n=n==""?m.is(":reset")?"Reset":"Submit":n;
        l.html(n);
        m.css("opacity",0);
        m.wrap(p);
        m.wrap(l);
        p=m.closest("div");
        l=m.closest("span");
        if(m.is(":disabled")){
            p.addClass(k.disabledClass)
            }
            p.bind({
            "mouseenter.uniform":function(){
                p.addClass(k.hoverClass)
                },
            "mouseleave.uniform":function(){
                p.removeClass(k.hoverClass);
                p.removeClass(k.activeClass)
                },
            "mousedown.uniform touchbegin.uniform":function(){
                p.addClass(k.activeClass)
                },
            "mouseup.uniform touchend.uniform":function(){
                p.removeClass(k.activeClass)
                },
            "click.uniform touchend.uniform":function(r){
                if(a(r.target).is("span")||a(r.target).is("div")){
                    if(o[0].dispatchEvent){
                        var q=document.createEvent("MouseEvents");
                        q.initEvent("click",true,true);
                        o[0].dispatchEvent(q)
                        }else{
                        o[0].click()
                        }
                    }
            }
        });
o.bind({
    "focus.uniform":function(){
        p.addClass(k.focusClass)
        },
    "blur.uniform":function(){
        p.removeClass(k.focusClass)
        }
    });
a.uniform.noSelect(p);
b(o)
}
function e(o){
    var m=a(o);
    var p=a("<div />"),l=a("<span />");
    if(!m.css("display")=="none"&&k.autoHide){
        p.hide()
        }
        p.addClass(k.selectClass);
    if(k.useID&&o.attr("id")!=""){
        p.attr("id",k.idPrefix+"-"+o.attr("id"))
        }
        var n=o.find(":selected:first");
    if(n.length==0){
        n=o.find("option:first")
        }
        l.html(n.html());
    o.css("opacity",0);
    o.wrap(p);
    o.before(l);
    p=o.parent("div");
    l=o.siblings("span");
    o.bind({
        "change.uniform":function(){
            l.text(o.find(":selected").html());
            p.removeClass(k.activeClass)
            },
        "focus.uniform":function(){
            p.addClass(k.focusClass)
            },
        "blur.uniform":function(){
            p.removeClass(k.focusClass);
            p.removeClass(k.activeClass)
            },
        "mousedown.uniform touchbegin.uniform":function(){
            p.addClass(k.activeClass)
            },
        "mouseup.uniform touchend.uniform":function(){
            p.removeClass(k.activeClass)
            },
        "click.uniform touchend.uniform":function(){
            p.removeClass(k.activeClass)
            },
        "mouseenter.uniform":function(){
            p.addClass(k.hoverClass)
            },
        "mouseleave.uniform":function(){
            p.removeClass(k.hoverClass);
            p.removeClass(k.activeClass)
            },
        "keyup.uniform":function(){
            l.text(o.find(":selected").html())
            }
        });
if(a(o).attr("disabled")){
    p.addClass(k.disabledClass)
    }
    a.uniform.noSelect(l);
b(o)
}
function f(n){
    var m=a(n);
    var o=a("<div />"),l=a("<span />");
    if(!m.css("display")=="none"&&k.autoHide){
        o.hide()
        }
        o.addClass(k.checkboxClass);
    if(k.useID&&n.attr("id")!=""){
        o.attr("id",k.idPrefix+"-"+n.attr("id"))
        }
        a(n).wrap(o);
    a(n).wrap(l);
    l=n.parent();
    o=l.parent();
    a(n).css("opacity",0).bind({
        "focus.uniform":function(){
            o.addClass(k.focusClass)
            },
        "blur.uniform":function(){
            o.removeClass(k.focusClass)
            },
        "click.uniform touchend.uniform":function(){
            if(!a(n).attr("checked")){
                l.removeClass(k.checkedClass)
                }else{
                l.addClass(k.checkedClass)
                }
            },
    "mousedown.uniform touchbegin.uniform":function(){
        o.addClass(k.activeClass)
        },
    "mouseup.uniform touchend.uniform":function(){
        o.removeClass(k.activeClass)
        },
    "mouseenter.uniform":function(){
        o.addClass(k.hoverClass)
        },
    "mouseleave.uniform":function(){
        o.removeClass(k.hoverClass);
        o.removeClass(k.activeClass)
        }
    });
if(a(n).attr("checked")){
    l.addClass(k.checkedClass)
    }
    if(a(n).attr("disabled")){
    o.addClass(k.disabledClass)
    }
    b(n)
}
function c(n){
    var m=a(n);
    var o=a("<div />"),l=a("<span />");
    if(!m.css("display")=="none"&&k.autoHide){
        o.hide()
        }
        o.addClass(k.radioClass);
    if(k.useID&&n.attr("id")!=""){
        o.attr("id",k.idPrefix+"-"+n.attr("id"))
        }
        a(n).wrap(o);
    a(n).wrap(l);
    l=n.parent();
    o=l.parent();
    a(n).css("opacity",0).bind({
        "focus.uniform":function(){
            o.addClass(k.focusClass)
            },
        "blur.uniform":function(){
            o.removeClass(k.focusClass)
            },
        "click.uniform touchend.uniform":function(){
            if(!a(n).attr("checked")){
                l.removeClass(k.checkedClass)
                }else{
                var p=k.radioClass.split(" ")[0];
                a("."+p+" span."+k.checkedClass+":has([name='"+a(n).attr("name")+"'])").removeClass(k.checkedClass);
                l.addClass(k.checkedClass)
                }
            },
    "mousedown.uniform touchend.uniform":function(){
        if(!a(n).is(":disabled")){
            o.addClass(k.activeClass)
            }
        },
    "mouseup.uniform touchbegin.uniform":function(){
        o.removeClass(k.activeClass)
        },
    "mouseenter.uniform touchend.uniform":function(){
        o.addClass(k.hoverClass)
        },
    "mouseleave.uniform":function(){
        o.removeClass(k.hoverClass);
        o.removeClass(k.activeClass)
        }
    });
if(a(n).attr("checked")){
    l.addClass(k.checkedClass)
    }
    if(a(n).attr("disabled")){
    o.addClass(k.disabledClass)
    }
    b(n)
}
function h(q){
    var o=a(q);
    var r=a("<div />"),p=a("<span>"+k.fileDefaultText+"</span>"),m=a("<span>"+k.fileBtnText+"</span>");
    if(!o.css("display")=="none"&&k.autoHide){
        r.hide()
        }
        r.addClass(k.fileClass);
    p.addClass(k.filenameClass);
    m.addClass(k.fileBtnClass);
    if(k.useID&&o.attr("id")!=""){
        r.attr("id",k.idPrefix+"-"+o.attr("id"))
        }
        o.wrap(r);
    o.after(m);
    o.after(p);
    r=o.closest("div");
    p=o.siblings("."+k.filenameClass);
    m=o.siblings("."+k.fileBtnClass);
    if(!o.attr("size")){
        var l=r.width();
        o.attr("size",l/10)
        }
        var n=function(){
        var s=o.val();
        if(s===""){
            s=k.fileDefaultText
            }else{
            s=s.split(/[\/\\]+/);
            s=s[(s.length-1)]
            }
            p.text(s)
        };
        
    n();
    o.css("opacity",0).bind({
        "focus.uniform":function(){
            r.addClass(k.focusClass)
            },
        "blur.uniform":function(){
            r.removeClass(k.focusClass)
            },
        "mousedown.uniform":function(){
            if(!a(q).is(":disabled")){
                r.addClass(k.activeClass)
                }
            },
    "mouseup.uniform":function(){
        r.removeClass(k.activeClass)
        },
    "mouseenter.uniform":function(){
        r.addClass(k.hoverClass)
        },
    "mouseleave.uniform":function(){
        r.removeClass(k.hoverClass);
        r.removeClass(k.activeClass)
        }
    });
if(a.browser.msie){
    o.bind("click.uniform.ie7",function(){
        setTimeout(n,0)
        })
    }else{
    o.bind("change.uniform",n)
    }
    if(o.attr("disabled")){
    r.addClass(k.disabledClass)
    }
    a.uniform.noSelect(p);
a.uniform.noSelect(m);
b(q)
}
a.uniform.restore=function(l){
    if(l==undefined){
        l=a(a.uniform.elements)
        }
        a(l).each(function(){
        if(a(this).is(":checkbox")){
            a(this).unwrap().unwrap()
            }else{
            if(a(this).is("select")){
                a(this).siblings("span").remove();
                a(this).unwrap()
                }else{
                if(a(this).is(":radio")){
                    a(this).unwrap().unwrap()
                    }else{
                    if(a(this).is(":file")){
                        a(this).siblings("span").remove();
                        a(this).unwrap()
                        }else{
                        if(a(this).is("button, :submit, :reset, a, input[type='button']")){
                            a(this).unwrap().unwrap()
                            }
                        }
                }
        }
    }
a(this).unbind(".uniform");
a(this).css("opacity","1");
var m=a.inArray(a(l),a.uniform.elements);
a.uniform.elements.splice(m,1)
})
};

function b(l){
    l=a(l).get();
    if(l.length>1){
        a.each(l,function(m,n){
            a.uniform.elements.push(n)
            })
        }else{
        a.uniform.elements.push(l)
        }
    }
a.uniform.noSelect=function(l){
    function m(){
        return false
        }
        a(l).each(function(){
        this.onselectstart=this.ondragstart=m;
        a(this).mousedown(m).css({
            MozUserSelect:"none"
        })
        })
    };
    
a.uniform.update=function(l){
    if(l==undefined){
        l=a(a.uniform.elements)
        }
        l=a(l);
    l.each(function(){
        var n=a(this);
        if(n.is("select")){
            var m=n.siblings("span");
            var p=n.parent("div");
            p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);
            m.html(n.find(":selected").html());
            if(n.is(":disabled")){
                p.addClass(k.disabledClass)
                }else{
                p.removeClass(k.disabledClass)
                }
            }else{
        if(n.is(":checkbox")){
            var m=n.closest("span");
            var p=n.closest("div");
            p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);
            m.removeClass(k.checkedClass);
            if(n.is(":checked")){
                m.addClass(k.checkedClass)
                }
                if(n.is(":disabled")){
                p.addClass(k.disabledClass)
                }else{
                p.removeClass(k.disabledClass)
                }
            }else{
        if(n.is(":radio")){
            var m=n.closest("span");
            var p=n.closest("div");
            p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);
            m.removeClass(k.checkedClass);
            if(n.is(":checked")){
                m.addClass(k.checkedClass)
                }
                if(n.is(":disabled")){
                p.addClass(k.disabledClass)
                }else{
                p.removeClass(k.disabledClass)
                }
            }else{
        if(n.is(":file")){
            var p=n.parent("div");
            var o=n.siblings(k.filenameClass);
            btnTag=n.siblings(k.fileBtnClass);
            p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);
            o.text(n.val());
            if(n.is(":disabled")){
                p.addClass(k.disabledClass)
                }else{
                p.removeClass(k.disabledClass)
                }
            }else{
        if(n.is(":submit")||n.is(":reset")||n.is("button")||n.is("a")||l.is("input[type=button]")){
            var p=n.closest("div");
            p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);
            if(n.is(":disabled")){
                p.addClass(k.disabledClass)
                }else{
                p.removeClass(k.disabledClass)
                }
            }
    }
}
}
}
})
};

return this.each(function(){
    if(a.support.selectOpacity){
        var l=a(this);
        if(l.is("select")){
            if(l.attr("multiple")!=true){
                if(l.attr("size")==undefined||l.attr("size")<=1){
                    e(l)
                    }
                }
        }else{
    if(l.is(":checkbox")){
        f(l)
        }else{
        if(l.is(":radio")){
            c(l)
            }else{
            if(l.is(":file")){
                h(l)
                }else{
                if(l.is(":text, :password, input[type='email']")){
                    j(l)
                    }else{
                    if(l.is("textarea")){
                        g(l)
                        }else{
                        if(l.is("a")||l.is(":submit")||l.is(":reset")||l.is("button")||l.is("input[type=button]")){
                            i(l)
                            }
                        }
                }
        }
}
}
}
}
})
}
})(jQuery);
(function(a){
    a.extend(a.fn,{
        validate:function(b){
            if(!this.length){
                b&&b.debug&&window.console&&console.warn("nothing selected, can't validate, returning nothing");
                return
            }
            var c=a.data(this[0],"validator");
            if(c){
                return c
                }
                c=new a.validator(b,this[0]);
            a.data(this[0],"validator",c);
            if(c.settings.onsubmit){
                this.find("input, button").filter(".cancel").click(function(){
                    c.cancelSubmit=true
                    });
                if(c.settings.submitHandler){
                    this.find("input, button").filter(":submit").click(function(){
                        c.submitButton=this
                        })
                    }
                    this.submit(function(d){
                    if(c.settings.debug){
                        d.preventDefault()
                        }
                        function e(){
                        if(c.settings.submitHandler){
                            if(c.submitButton){
                                var f=a("<input type='hidden'/>").attr("name",c.submitButton.name).val(c.submitButton.value).appendTo(c.currentForm)
                                }
                                c.settings.submitHandler.call(c,c.currentForm);
                            if(c.submitButton){
                                f.remove()
                                }
                                return false
                            }
                            return true
                        }
                        if(c.cancelSubmit){
                        c.cancelSubmit=false;
                        return e()
                        }
                        if(c.form()){
                        if(c.pendingRequest){
                            c.formSubmitted=true;
                            return false
                            }
                            return e()
                        }else{
                        c.focusInvalid();
                        return false
                        }
                    })
            }
            return c
        },
    valid:function(){
        if(a(this[0]).is("form")){
            return this.validate().form()
            }else{
            var c=true;
            var b=a(this[0].form).validate();
            this.each(function(){
                c&=b.element(this)
                });
            return c
            }
        },
    removeAttrs:function(d){
        var b={
        },c=this;
        a.each(d.split(/\s/),function(e,f){
            b[f]=c.attr(f);
            c.removeAttr(f)
            });
        return b
        },
    rules:function(e,b){
        var g=this[0];
        if(e){
            var d=a.data(g.form,"validator").settings;
            var i=d.rules;
            var j=a.validator.staticRules(g);
            switch(e){
                case"add":
                    a.extend(j,a.validator.normalizeRule(b));
                    i[g.name]=j;
                    if(b.messages){
                    d.messages[g.name]=a.extend(d.messages[g.name],b.messages)
                    }
                    break;
                case"remove":
                    if(!b){
                    delete i[g.name];
                    return j
                    }
                    var h={
                };
                
                a.each(b.split(/\s/),function(k,l){
                    h[l]=j[l];
                    delete j[l]
                });
                return h
                }
                }
        var f=a.validator.normalizeRules(a.extend({
        },a.validator.metadataRules(g),a.validator.classRules(g),a.validator.attributeRules(g),a.validator.staticRules(g)),g);
    if(f.required){
        var c=f.required;
        delete f.required;
        f=a.extend({
            required:c
        },f)
        }
        return f
    }
});
a.extend(a.expr[":"],{
    blank:function(b){
        return !a.trim(""+b.value)
        },
    filled:function(b){
        return !!a.trim(""+b.value)
        },
    unchecked:function(b){
        return !b.checked
        }
    });
a.validator=function(b,c){
    this.settings=a.extend(true,{
        },a.validator.defaults,b);
    this.currentForm=c;
    this.init()
    };
    
a.validator.format=function(b,c){
    if(arguments.length==1){
        return function(){
            var d=a.makeArray(arguments);
            d.unshift(b);
            return a.validator.format.apply(this,d)
            }
        }
    if(arguments.length>2&&c.constructor!=Array){
    c=a.makeArray(arguments).slice(1)
    }
    if(c.constructor!=Array){
    c=[c]
    }
    a.each(c,function(d,e){
    b=b.replace(new RegExp("\\{"+d+"\\}","g"),e)
    });
return b
};

a.extend(a.validator,{
    defaults:{
        messages:{
        },
        groups:{
        },
        rules:{
        },
        errorClass:"error",
        validClass:"valid",
        errorElement:"div",
        focusInvalid:true,
        errorContainer:a([]),
        errorLabelContainer:a([]),
        onsubmit:false,
        ignore:[],
        ignoreTitle:false,
        onfocusin:function(b){
            this.lastActive=b;
            if(this.settings.focusCleanup&&!this.blockFocusCleanup){
                this.settings.unhighlight&&this.settings.unhighlight.call(this,b,this.settings.errorClass,this.settings.validClass);
                this.addWrapper(this.errorsFor(b)).hide()
                }
            },
    onfocusout:function(b){
        if(!this.checkable(b)&&(b.name in this.submitted||!this.optional(b))){
            this.element(b)
            }
        },
onkeyup:function(b){
    if(b.name in this.submitted||b==this.lastElement){
        this.element(b)
        }
    },
onclick:function(b){
    if(b.name in this.submitted){
        this.element(b)
        }else{
        if(b.parentNode.name in this.submitted){
            this.element(b.parentNode)
            }
        }
},
highlight:function(d,b,c){
    if(d.type==="radio"){
        this.findByName(d.name).addClass(b).removeClass(c)
        }else{
        a(d).addClass(b).removeClass(c)
        }
    },
unhighlight:function(d,b,c){
    if(d.type==="radio"){
        this.findByName(d.name).removeClass(b).addClass(c)
        }else{
        a(d).removeClass(b).addClass(c)
        }
    }
},
setDefaults:function(b){
    a.extend(a.validator.defaults,b)
    },
messages:{
    required:"Este campo é obrigatório.",
    remote:"Please fix this field.",
    email:"Please enter a valid email address.",
    url:"Please enter a valid URL.",
    date:"Please enter a valid date.",
    dateISO:"Please enter a valid date (ISO).",
    number:"Please enter a valid number.",
    digits:"Please enter only digits.",
    creditcard:"Please enter a valid credit card number.",
    equalTo:"Please enter the same value again.",
    accept:"Please enter a value with a valid extension.",
    maxlength:a.validator.format("Please enter no more than {0} characters."),
    minlength:a.validator.format("Please enter at least {0} characters."),
    rangelength:a.validator.format("Please enter a value between {0} and {1} characters long."),
    range:a.validator.format("Please enter a value between {0} and {1}."),
    max:a.validator.format("Please enter a value less than or equal to {0}."),
    min:a.validator.format("Please enter a value greater than or equal to {0}.")
    },
autoCreateRanges:false,
prototype:{
    init:function(){
        this.labelContainer=a(this.settings.errorLabelContainer);
        this.errorContext=this.labelContainer.length&&this.labelContainer||a(this.currentForm);
        this.containers=a(this.settings.errorContainer).add(this.settings.errorLabelContainer);
        this.submitted={
        };
        
        this.valueCache={
        };
        
        this.pendingRequest=0;
        this.pending={
        };
        
        this.invalid={
        };
        
        this.reset();
        var b=(this.groups={
            });
        a.each(this.settings.groups,function(e,f){
            a.each(f.split(/\s/),function(h,g){
                b[g]=e
                })
            });
        var d=this.settings.rules;
        a.each(d,function(e,f){
            d[e]=a.validator.normalizeRule(f)
            });
        function c(g){
            var f=a.data(this[0].form,"validator"),e="on"+g.type.replace(/^validate/,"");
            f.settings[e]&&f.settings[e].call(f,this[0])
            }
            a(this.currentForm).validateDelegate(":text, :password, :file, select, textarea","focusin focusout keyup",c).validateDelegate(":radio, :checkbox, select, option","click",c);
        if(this.settings.invalidHandler){
            a(this.currentForm).bind("invalid-form.validate",this.settings.invalidHandler)
            }
        },
form:function(){
    this.checkForm();
    a.extend(this.submitted,this.errorMap);
    this.invalid=a.extend({
        },this.errorMap);
    if(!this.valid()){
        a(this.currentForm).triggerHandler("invalid-form",[this])
        }
        this.showErrors();
    return this.valid()
    },
checkForm:function(){
    this.prepareForm();
    for(var b=0,c=(this.currentElements=this.elements());c[b];b++){
        this.check(c[b])
        }
        return this.valid()
    },
element:function(c){
    c=this.clean(c);
    this.lastElement=c;
    this.prepareElement(c);
    this.currentElements=a(c);
    var b=this.check(c);
    if(b){
        delete this.invalid[c.name]
    }else{
        this.invalid[c.name]=true
        }
        if(!this.numberOfInvalids()){
        this.toHide=this.toHide.add(this.containers)
        }
        this.showErrors();
    return b
    },
showErrors:function(c){
    if(c){
        a.extend(this.errorMap,c);
        this.errorList=[];
        for(var b in c){
            this.errorList.push({
                message:c[b],
                element:this.findByName(b)[0]
                })
            }
            this.successList=a.grep(this.successList,function(d){
            return !(d.name in c)
            })
        }
        this.settings.showErrors?this.settings.showErrors.call(this,this.errorMap,this.errorList):this.defaultShowErrors()
    },
resetForm:function(){
    if(a.fn.resetForm){
        a(this.currentForm).resetForm()
        }
        this.submitted={
    };
    
    this.prepareForm();
    this.hideErrors();
    this.elements().removeClass(this.settings.errorClass)
    },
numberOfInvalids:function(){
    return this.objectLength(this.invalid)
    },
objectLength:function(d){
    var c=0;
    for(var b in d){
        c++
    }
    return c
    },
hideErrors:function(){
    this.addWrapper(this.toHide).hide()
    },
valid:function(){
    return this.size()==0
    },
size:function(){
    return this.errorList.length
    },
focusInvalid:function(){
    if(this.settings.focusInvalid){
        try{
            a(this.findLastActive()||this.errorList.length&&this.errorList[0].element||[]).filter(":visible").focus().trigger("focusin")
            }catch(b){
        }
    }
},
findLastActive:function(){
    var b=this.lastActive;
    return b&&a.grep(this.errorList,function(c){
        return c.element.name==b.name
        }).length==1&&b
    },
elements:function(){
    var c=this,b={
    };
    
    return a(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function(){
        !this.name&&c.settings.debug&&window.console&&console.error("%o has no name assigned",this);
        if(this.name in b||!c.objectLength(a(this).rules())){
            return false
            }
            b[this.name]=true;
        return true
        })
    },
clean:function(b){
    return a(b)[0]
    },
errors:function(){
    return a(this.settings.errorElement+"."+this.settings.errorClass,this.errorContext)
    },
reset:function(){
    this.successList=[];
    this.errorList=[];
    this.errorMap={
    };
    
    this.toShow=a([]);
    this.toHide=a([]);
    this.currentElements=a([])
    },
prepareForm:function(){
    this.reset();
    this.toHide=this.errors().add(this.containers)
    },
prepareElement:function(b){
    this.reset();
    this.toHide=this.errorsFor(b)
    },
check:function(c){
    c=this.clean(c);
    if(this.checkable(c)){
        c=this.findByName(c.name).not(this.settings.ignore)[0]
        }
        var h=a(c).rules();
    var d=false;
    for(var i in h){
        var g={
            method:i,
            parameters:h[i]
            };
            
        try{
            var b=a.validator.methods[i].call(this,c.value.replace(/\r/g,""),c,g.parameters);
            if(b=="dependency-mismatch"){
                d=true;
                continue
            }
            d=false;
            if(b=="pending"){
                this.toHide=this.toHide.not(this.errorsFor(c));
                return
            }
            if(!b){
                this.formatAndAdd(c,g);
                return false
                }
            }catch(f){
        this.settings.debug&&window.console&&console.log("exception occured when checking element "+c.id+", check the '"+g.method+"' method",f);
        throw f
        }
    }
    if(d){
    return
}
if(this.objectLength(h)){
    this.successList.push(c)
    }
    return true
},
customMetaMessage:function(b,d){
    if(!a.metadata){
        return
    }
    var c=this.settings.meta?a(b).metadata()[this.settings.meta]:a(b).metadata();
    return c&&c.messages&&c.messages[d]
    },
customMessage:function(c,d){
    var b=this.settings.messages[c];
    return b&&(b.constructor==String?b:b[d])
    },
findDefined:function(){
    for(var b=0;b<arguments.length;b++){
        if(arguments[b]!==undefined){
            return arguments[b]
            }
        }
    return undefined
},
defaultMessage:function(b,c){
    return this.findDefined(this.customMessage(b.name,c),this.customMetaMessage(b,c),!this.settings.ignoreTitle&&b.title||undefined,a.validator.messages[c],"<strong>Warning: No message defined for "+b.name+"</strong>")
    },
formatAndAdd:function(c,e){
    var d=this.defaultMessage(c,e.method),b=/\$?\{(\d+)\}/g;
    if(typeof d=="function"){
        d=d.call(this,e.parameters,c)
        }else{
        if(b.test(d)){
            d=jQuery.format(d.replace(b,"{$1}"),e.parameters)
            }
        }
    this.errorList.push({
    message:d,
    element:c
});
this.errorMap[c.name]=d;
this.submitted[c.name]=d
},
addWrapper:function(b){
    if(this.settings.wrapper){
        b=b.add(b.parent(this.settings.wrapper))
        }
        return b
    },
defaultShowErrors:function(){
    for(var c=0;this.errorList[c];c++){
        var b=this.errorList[c];
        this.settings.highlight&&this.settings.highlight.call(this,b.element,this.settings.errorClass,this.settings.validClass);
        this.showLabel(b.element,b.message)
        }
        if(this.errorList.length){
        this.toShow=this.toShow.add(this.containers)
        }
        if(this.settings.success){
        for(var c=0;this.successList[c];c++){
            this.showLabel(this.successList[c])
            }
        }
        if(this.settings.unhighlight){
    for(var c=0,d=this.validElements();d[c];c++){
        this.settings.unhighlight.call(this,d[c],this.settings.errorClass,this.settings.validClass)
        }
    }
    this.toHide=this.toHide.not(this.toShow);
this.hideErrors();
this.addWrapper(this.toShow).show()
},
validElements:function(){
    return this.currentElements.not(this.invalidElements())
    },
invalidElements:function(){
    return a(this.errorList).map(function(){
        return this.element
        })
    },
showLabel:function(c,d){
    var b=this.errorsFor(c);
    if(b.length){
        b.removeClass().addClass(this.settings.errorClass);
        b.attr("generated")&&b.html(d)
        }else{
        b=a("<"+this.settings.errorElement+"/>").attr({
            "for":this.idOrName(c),
            generated:true
        }).addClass(this.settings.errorClass).html(d||"");
        if(this.settings.wrapper){
            b=b.hide().show().wrap("<"+this.settings.wrapper+"/>").parent()
            }
            if(!this.labelContainer.append(b).length){
            this.settings.errorPlacement?this.settings.errorPlacement(b,a(c)):b.insertAfter(c)
            }
        }
    if(!d&&this.settings.success){
    b.text("");
    typeof this.settings.success=="string"?b.addClass(this.settings.success):this.settings.success(b)
    }
    this.toShow=this.toShow.add(b)
},
errorsFor:function(c){
    var b=this.idOrName(c);
    return this.errors().filter(function(){
        return a(this).attr("for")==b
        })
    },
idOrName:function(b){
    return this.groups[b.name]||(this.checkable(b)?b.name:b.id||b.name)
    },
checkable:function(b){
    return/radio|checkbox/i.test(b.type)
    },
findByName:function(b){
    var c=this.currentForm;
    return a(document.getElementsByName(b)).map(function(d,e){
        return e.form==c&&e.name==b&&e||null
        })
    },
getLength:function(c,b){
    switch(b.nodeName.toLowerCase()){
        case"select":
            return a("option:selected",b).length;
        case"input":
            if(this.checkable(b)){
            return this.findByName(b.name).filter(":checked").length
            }
        }
        return c.length
},
depend:function(c,b){
    return this.dependTypes[typeof c]?this.dependTypes[typeof c](c,b):true
    },
dependTypes:{
    "boolean":function(c,b){
        return c
        },
    string:function(c,b){
        return !!a(c,b.form).length
        },
    "function":function(c,b){
        return c(b)
        }
    },
optional:function(b){
    return !a.validator.methods.required.call(this,a.trim(b.value),b)&&"dependency-mismatch"
    },
startRequest:function(b){
    if(!this.pending[b.name]){
        this.pendingRequest++;
        this.pending[b.name]=true
        }
    },
stopRequest:function(b,c){
    this.pendingRequest--;
    if(this.pendingRequest<0){
        this.pendingRequest=0
        }
        delete this.pending[b.name];
    if(c&&this.pendingRequest==0&&this.formSubmitted&&this.form()){
        a(this.currentForm).submit();
        this.formSubmitted=false
        }else{
        if(!c&&this.pendingRequest==0&&this.formSubmitted){
            a(this.currentForm).triggerHandler("invalid-form",[this]);
            this.formSubmitted=false
            }
        }
},
previousValue:function(b){
    return a.data(b,"previousValue")||a.data(b,"previousValue",{
        old:null,
        valid:true,
        message:this.defaultMessage(b,"remote")
        })
    }
},
classRuleSettings:{
    required:{
        required:true
    },
    email:{
        email:true
    },
    url:{
        url:true
    },
    date:{
        date:true
    },
    dateISO:{
        dateISO:true
    },
    dateDE:{
        dateDE:true
    },
    number:{
        number:true
    },
    numberDE:{
        numberDE:true
    },
    digits:{
        digits:true
    },
    creditcard:{
        creditcard:true
    }
},
addClassRules:function(b,c){
    b.constructor==String?this.classRuleSettings[b]=c:a.extend(this.classRuleSettings,b)
    },
classRules:function(c){
    var d={
    };
    
    var b=a(c).attr("class");
    b&&a.each(b.split(" "),function(){
        if(this in a.validator.classRuleSettings){
            a.extend(d,a.validator.classRuleSettings[this])
            }
        });
return d
},
attributeRules:function(c){
    var e={
    };
    
    var b=a(c);
    for(var f in a.validator.methods){
        var d=b.attr(f);
        if(d){
            e[f]=d
            }
        }
    if(e.maxlength&&/-1|2147483647|524288/.test(e.maxlength)){
    delete e.maxlength
    }
    return e
},
metadataRules:function(b){
    if(!a.metadata){
        return{
        }
    }
    var c=a.data(b.form,"validator").settings.meta;
return c?a(b).metadata()[c]:a(b).metadata()
},
staticRules:function(c){
    var d={
    };
    
    var b=a.data(c.form,"validator");
    if(b.settings.rules){
        d=a.validator.normalizeRule(b.settings.rules[c.name])||{
        }
    }
    return d
},
normalizeRules:function(c,b){
    a.each(c,function(f,e){
        if(e===false){
            delete c[f];
            return
        }
        if(e.param||e.depends){
            var d=true;
            switch(typeof e.depends){
                case"string":
                    d=!!a(e.depends,b.form).length;
                    break;
                case"function":
                    d=e.depends.call(b,b);
                    break
                    }
                    if(d){
                c[f]=e.param!==undefined?e.param:true
                }else{
                delete c[f]
            }
        }
    });
a.each(c,function(d,e){
    c[d]=a.isFunction(e)?e(b):e
    });
a.each(["minlength","maxlength","min","max"],function(){
    if(c[this]){
        c[this]=Number(c[this])
        }
    });
a.each(["rangelength","range"],function(){
    if(c[this]){
        c[this]=[Number(c[this][0]),Number(c[this][1])]
        }
    });
if(a.validator.autoCreateRanges){
    if(c.min&&c.max){
        c.range=[c.min,c.max];
        delete c.min;
        delete c.max
        }
        if(c.minlength&&c.maxlength){
        c.rangelength=[c.minlength,c.maxlength];
        delete c.minlength;
        delete c.maxlength
        }
    }
if(c.messages){
    delete c.messages
    }
    return c
},
normalizeRule:function(c){
    if(typeof c=="string"){
        var b={
        };
        
        a.each(c.split(/\s/),function(){
            b[this]=true
            });
        c=b
        }
        return c
    },
addMethod:function(b,d,c){
    a.validator.methods[b]=d;
    a.validator.messages[b]=c!=undefined?c:a.validator.messages[b];
    if(d.length<3){
        a.validator.addClassRules(b,a.validator.normalizeRule(b))
        }
    },
methods:{
    required:function(c,b,e){
        if(!this.depend(e,b)){
            return"dependency-mismatch"
            }
            switch(b.nodeName.toLowerCase()){
            case"select":
                var d=a(b).val();
                return d&&d.length>0;
            case"input":
                if(this.checkable(b)){
                return this.getLength(c,b)>0
                }
                default:
                return a.trim(c).length>0
                }
            },
remote:function(f,c,g){
    if(this.optional(c)){
        return"dependency-mismatch"
        }
        var d=this.previousValue(c);
    if(!this.settings.messages[c.name]){
        this.settings.messages[c.name]={
        }
    }
    d.originalMessage=this.settings.messages[c.name].remote;
this.settings.messages[c.name].remote=d.message;
g=typeof g=="string"&&{
    url:g
}||g;
if(this.pending[c.name]){
    return"pending"
    }
    if(d.old===f){
    return d.valid
    }
    d.old=f;
var b=this;
this.startRequest(c);
var e={
};

e[c.name]=f;
a.ajax(a.extend(true,{
    url:g,
    mode:"abort",
    port:"validate"+c.name,
    dataType:"json",
    data:e,
    success:function(i){
        b.settings.messages[c.name].remote=d.originalMessage;
        var k=i===true;
        if(k){
            var h=b.formSubmitted;
            b.prepareElement(c);
            b.formSubmitted=h;
            b.successList.push(c);
            b.showErrors()
            }else{
            var l={
            };
            
            var j=i||b.defaultMessage(c,"remote");
            l[c.name]=d.message=a.isFunction(j)?j(f):j;
            b.showErrors(l)
            }
            d.valid=k;
        b.stopRequest(c,k)
        }
    },g));
return"pending"
},
minlength:function(c,b,d){
    return this.optional(b)||this.getLength(a.trim(c),b)>=d
    },
maxlength:function(c,b,d){
    return this.optional(b)||this.getLength(a.trim(c),b)<=d
    },
rangelength:function(d,b,e){
    var c=this.getLength(a.trim(d),b);
    return this.optional(b)||(c>=e[0]&&c<=e[1])
    },
min:function(c,b,d){
    return this.optional(b)||c>=d
    },
max:function(c,b,d){
    return this.optional(b)||c<=d
    },
range:function(c,b,d){
    return this.optional(b)||(c>=d[0]&&c<=d[1])
    },
email:function(c,b){
    return this.optional(b)||/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(c)
},
url:function(c,b){
    return this.optional(b)||/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(c)}
,date:function(c,b){return this.optional(b)||!/Invalid|NaN/.test(new Date(c))}
,dateISO:function(c,b){return this.optional(b)||/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(c)}
,number:function(c,b){return this.optional(b)||/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(c)}
,digits:function(c,b){
    return this.optional(b)||/^\d+$/.test(c)
    },
creditcard:function(f,c){
    if(this.optional(c)){
        return"dependency-mismatch"
        }
        if(/[^0-9-]+/.test(f)){
        return false
        }
        var g=0,e=0,b=false;
    f=f.replace(/\D/g,"");
    for(var h=f.length-1;h>=0;h--){
        var d=f.charAt(h);
        var e=parseInt(d,10);
        if(b){
            if((e*=2)>9){
                e-=9
                }
            }
        g+=e;
    b=!b
    }
    return(g%10)==0
},
accept:function(c,b,d){
    d=typeof d=="string"?d.replace(/,/g,"|"):"png|jpe?g|gif";
    return this.optional(b)||c.match(new RegExp(".("+d+")$","i"))
    },
equalTo:function(c,b,e){
    var d=a(e).unbind(".validate-equalTo").bind("blur.validate-equalTo",function(){
        a(b).valid()
        });
    return c==d.val()
    }
}
});
a.format=a.validator.format
})(jQuery);
(function(c){
    var a={
    };
    
    if(c.ajaxPrefilter){
        c.ajaxPrefilter(function(f,e,g){
            var d=f.port;
            if(f.mode=="abort"){
                if(a[d]){
                    a[d].abort()
                    }
                    a[d]=g
                }
            })
    }else{
    var b=c.ajax;
    c.ajax=function(e){
        var f=("mode" in e?e:c.ajaxSettings).mode,d=("port" in e?e:c.ajaxSettings).port;
        if(f=="abort"){
            if(a[d]){
                a[d].abort()
                }
                return(a[d]=b.apply(this,arguments))
            }
            return b.apply(this,arguments)
        }
    }
})(jQuery);
(function(a){
    if(!jQuery.event.special.focusin&&!jQuery.event.special.focusout&&document.addEventListener){
        a.each({
            focus:"focusin",
            blur:"focusout"
        },function(c,b){
            a.event.special[b]={
                setup:function(){
                    this.addEventListener(c,d,true)
                    },
                teardown:function(){
                    this.removeEventListener(c,d,true)
                    },
                handler:function(f){
                    arguments[0]=a.event.fix(f);
                    arguments[0].type=b;
                    return a.event.handle.apply(this,arguments)
                    }
                };
            
        function d(f){
            f=a.event.fix(f);
            f.type=b;
            return a.event.handle.call(this,f)
            }
        })
}
a.extend(a.fn,{
    validateDelegate:function(d,c,b){
        return this.bind(c,function(e){
            var f=a(e.target);
            if(f.is(d)){
                return b.apply(f,arguments)
                }
            })
    }
})
})(jQuery);
/**
 * jQuery Validation Plugin 1.8.1
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * http://docs.jquery.com/Plugins/Validation
 *
 * Copyright (c) 2006 - 2011 Jörn Zaefferer
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
(function(c){
    c.extend(c.fn,{
        validate:function(a){
            if(this.length){
                var b=c.data(this[0],"validator");
                if(b)return b;
                b=new c.validator(a,this[0]);
                c.data(this[0],"validator",b);
                if(b.settings.onsubmit){
                    this.find("input, button").filter(".cancel").click(function(){
                        b.cancelSubmit=true
                        });
                    b.settings.submitHandler&&this.find("input, button").filter(":submit").click(function(){
                        b.submitButton=this
                        });
                    this.submit(function(d){
                        function e(){
                            if(b.settings.submitHandler){
                                if(b.submitButton)var f=c("<input type='hidden'/>").attr("name",
                                    b.submitButton.name).val(b.submitButton.value).appendTo(b.currentForm);
                                b.settings.submitHandler.call(b,b.currentForm);
                                b.submitButton&&f.remove();
                                return false
                                }
                                return true
                            }
                            b.settings.debug&&d.preventDefault();
                        if(b.cancelSubmit){
                            b.cancelSubmit=false;
                            return e()
                            }
                            if(b.form()){
                            if(b.pendingRequest){
                                b.formSubmitted=true;
                                return false
                                }
                                return e()
                            }else{
                            b.focusInvalid();
                            return false
                            }
                        })
                }
                return b
            }else a&&a.debug&&window.console&&console.warn("nothing selected, can't validate, returning nothing")
            },
    valid:function(){
        if(c(this[0]).is("form"))return this.validate().form();
        else{
            var a=true,b=c(this[0].form).validate();
            this.each(function(){
                a&=b.element(this)
                });
            return a
            }
        },
    removeAttrs:function(a){
        var b={
        },d=this;
        c.each(a.split(/\s/),function(e,f){
            b[f]=d.attr(f);
            d.removeAttr(f)
            });
        return b
        },
    rules:function(a,b){
        var d=this[0];
        if(a){
            var e=c.data(d.form,"validator").settings,f=e.rules,g=c.validator.staticRules(d);
            switch(a){
                case "add":
                    c.extend(g,c.validator.normalizeRule(b));
                    f[d.name]=g;
                    if(b.messages)e.messages[d.name]=c.extend(e.messages[d.name],b.messages);
                    break;
                case "remove":
                    if(!b){
                    delete f[d.name];
                    return g
                    }
                    var h={
                };
                
                c.each(b.split(/\s/),function(j,i){
                    h[i]=g[i];
                    delete g[i]
                });
                return h
                }
                }
        d=c.validator.normalizeRules(c.extend({
        },c.validator.metadataRules(d),c.validator.classRules(d),c.validator.attributeRules(d),c.validator.staticRules(d)),d);
    if(d.required){
        e=d.required;
        delete d.required;
        d=c.extend({
            required:e
        },d)
        }
        return d
    }
});
c.extend(c.expr[":"],{
    blank:function(a){
        return!c.trim(""+a.value)
        },
    filled:function(a){
        return!!c.trim(""+a.value)
        },
    unchecked:function(a){
        return!a.checked
        }
    });
c.validator=function(a,
    b){
    this.settings=c.extend(true,{
        },c.validator.defaults,a);
    this.currentForm=b;
    this.init()
    };
    
c.validator.format=function(a,b){
    if(arguments.length==1)return function(){
        var d=c.makeArray(arguments);
        d.unshift(a);
        return c.validator.format.apply(this,d)
        };
        
    if(arguments.length>2&&b.constructor!=Array)b=c.makeArray(arguments).slice(1);
    if(b.constructor!=Array)b=[b];
    c.each(b,function(d,e){
        a=a.replace(RegExp("\\{"+d+"\\}","g"),e)
        });
    return a
    };
    
c.extend(c.validator,{
    defaults:{
        messages:{
        },
        groups:{
        },
        rules:{
        },
        errorClass:"error",
        validClass:"valid",
        errorElement:"div",
        focusInvalid:true,
        errorContainer:c([]),
        errorLabelContainer:c([]),
        onsubmit:false,
        ignore:[],
        ignoreTitle:false,
        onfocusin:function(a){
            this.lastActive=a;
            if(this.settings.focusCleanup&&!this.blockFocusCleanup){
                this.settings.unhighlight&&this.settings.unhighlight.call(this,a,this.settings.errorClass,this.settings.validClass);
                this.addWrapper(this.errorsFor(a)).hide()
                }
            },
    onfocusout:function(a){
        if(!this.checkable(a)&&(a.name in this.submitted||!this.optional(a)))this.element(a)
            },
    onkeyup:function(a){
        if(a.name in this.submitted||a==this.lastElement)this.element(a)
            },
    onclick:function(a){
        if(a.name in this.submitted)this.element(a);else a.parentNode.name in this.submitted&&this.element(a.parentNode)
        },
highlight:function(a,b,d){
        a.type==="radio"?this.findByName(a.name).addClass(b).removeClass(d):c(a).addClass(b).removeClass(d)
        },
    unhighlight:function(a,b,d){
        a.type==="radio"?this.findByName(a.name).removeClass(b).addClass(d):c(a).removeClass(b).addClass(d)
        }
    },
setDefaults:function(a){
    c.extend(c.validator.defaults,
        a)
    },
messages:{
    required:"This field is required.",
    remote:"Please fix this field.",
    email:"Please enter a valid email address.",
    url:"Please enter a valid URL.",
    date:"Please enter a valid date.",
    dateISO:"Please enter a valid date (ISO).",
    number:"Please enter a valid number.",
    digits:"Please enter only digits.",
    creditcard:"Please enter a valid credit card number.",
    equalTo:"Please enter the same value again.",
    accept:"Please enter a value with a valid extension.",
    maxlength:c.validator.format("Please enter no more than {0} characters."),
    minlength:c.validator.format("Please enter at least {0} characters."),
    rangelength:c.validator.format("Please enter a value between {0} and {1} characters long."),
    range:c.validator.format("Please enter a value between {0} and {1}."),
    max:c.validator.format("Please enter a value less than or equal to {0}."),
    min:c.validator.format("Please enter a value greater than or equal to {0}.")
    },
autoCreateRanges:false,
prototype:{
    init:function(){
    function a(e){
    var f=c.data(this[0].form,"validator");e="on"+e.type.replace(/^validate/,
        "");f.settings[e]&&f.settings[e].call(f,this[0])
    }
    this.labelContainer=c(this.settings.errorLabelContainer);this.errorContext=this.labelContainer.length&&this.labelContainer||c(this.currentForm);this.containers=c(this.settings.errorContainer).add(this.settings.errorLabelContainer);this.submitted={
    };
    this.valueCache={
    };
    this.pendingRequest=0;this.pending={
    };
    this.invalid={
    };
    this.reset();var b=this.groups={
    };
    c.each(this.settings.groups,function(e,f){
        c.each(f.split(/\s/),function(g,h){
            b[h]=e
            })
        });var d=this.settings.rules;
    c.each(d,function(e,f){
        d[e]=c.validator.normalizeRule(f)
        });c(this.currentForm).validateDelegate(":text, :password, :file, select, textarea","focusin focusout keyup",a).validateDelegate(":radio, :checkbox, select, option","click",a);this.settings.invalidHandler&&c(this.currentForm).bind("invalid-form.validate",this.settings.invalidHandler)
    },
    form:function(){
    this.checkForm();c.extend(this.submitted,this.errorMap);this.invalid=c.extend({
        },this.errorMap);this.valid()||c(this.currentForm).triggerHandler("invalid-form",
        [this]);this.showErrors();return this.valid()
    },
    checkForm:function(){
    this.prepareForm();for(var a=0,b=this.currentElements=this.elements();b[a];a++)this.check(b[a]);return this.valid()
    },
    element:function(a){
    this.lastElement=a=this.clean(a);this.prepareElement(a);this.currentElements=c(a);var b=this.check(a);if(b)delete this.invalid[a.name];else this.invalid[a.name]=true;if(!this.numberOfInvalids())this.toHide=this.toHide.add(this.containers);this.showErrors();return b
    },
showErrors:function(a){
    if(a){
    c.extend(this.errorMap,
        a);this.errorList=[];for(var b in a)this.errorList.push({
        message:a[b],
    element:this.findByName(b)[0]
        });this.successList=c.grep(this.successList,function(d){
        return!(d.name in a)
        })
    }
    this.settings.showErrors?this.settings.showErrors.call(this,this.errorMap,this.errorList):this.defaultShowErrors()
    },
resetForm:function(){
    c.fn.resetForm&&c(this.currentForm).resetForm();this.submitted={
    };
    this.prepareForm();this.hideErrors();this.elements().removeClass(this.settings.errorClass)
    },
numberOfInvalids:function(){
    return this.objectLength(this.invalid)
    },
    objectLength:function(a){
    var b=0,d;for(d in a)b++;return b
    },
hideErrors:function(){
    this.addWrapper(this.toHide).hide()
    },
valid:function(){
    return this.size()==0
    },
size:function(){
    return this.errorList.length
    },
focusInvalid:function(){
    if(this.settings.focusInvalid)try{
    c(this.findLastActive()||this.errorList.length&&this.errorList[0].element||[]).filter(":visible").focus().trigger("focusin")
    }catch(a){
    }
},
findLastActive:function(){
    var a=this.lastActive;return a&&c.grep(this.errorList,function(b){
        return b.element.name==
        a.name
        }).length==1&&a
    },
elements:function(){
    var a=this,b={
    };
    return c(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function(){
        !this.name&&a.settings.debug&&window.console&&console.error("%o has no name assigned",this);if(this.name in b||!a.objectLength(c(this).rules()))return false;return b[this.name]=true
        })
    },
clean:function(a){
    return c(a)[0]
    },
errors:function(){
    return c(this.settings.errorElement+"."+this.settings.errorClass,
        this.errorContext)
    },
reset:function(){
    this.successList=[];this.errorList=[];this.errorMap={
    };
    this.toShow=c([]);this.toHide=c([]);this.currentElements=c([])
    },
prepareForm:function(){
    this.reset();this.toHide=this.errors().add(this.containers)
    },
prepareElement:function(a){
    this.reset();this.toHide=this.errorsFor(a)
    },
check:function(a){
    a=this.clean(a);if(this.checkable(a))a=this.findByName(a.name).not(this.settings.ignore)[0];var b=c(a).rules(),d=false,e;for(e in b){
    var f={
    method:e,
    parameters:b[e]
    };
    try{
    var g=
    c.validator.methods[e].call(this,a.value.replace(/\r/g,""),a,f.parameters);if(g=="dependency-mismatch")d=true;else{
    d=false;
    if(g=="pending"){
        this.toHide=this.toHide.not(this.errorsFor(a));
        return
    }
    if(!g){
        this.formatAndAdd(a,f);
        return false
        }
    }
}catch(h){
    this.settings.debug&&window.console&&console.log("exception occured when checking element "+a.id+", check the '"+f.method+"' method",h);
    throw h;
}
}
if(!d){
    this.objectLength(b)&&this.successList.push(a);
    return true
    }
},
customMetaMessage:function(a,b){
    if(c.metadata){
        var d=
        this.settings.meta?c(a).metadata()[this.settings.meta]:c(a).metadata();
        return d&&d.messages&&d.messages[b]
        }
    },
customMessage:function(a,b){
    var d=this.settings.messages[a];
    return d&&(d.constructor==String?d:d[b])
    },
findDefined:function(){
    for(var a=0;a<arguments.length;a++)if(arguments[a]!==undefined)return arguments[a]
        },
defaultMessage:function(a,b){
    return this.findDefined(this.customMessage(a.name,b),this.customMetaMessage(a,b),!this.settings.ignoreTitle&&a.title||undefined,c.validator.messages[b],"<strong>Warning: No message defined for "+
        a.name+"</strong>")
    },
formatAndAdd:function(a,b){
    var d=this.defaultMessage(a,b.method),e=/\$?\{(\d+)\}/g;
    if(typeof d=="function")d=d.call(this,b.parameters,a);
    else if(e.test(d))d=jQuery.format(d.replace(e,"{$1}"),b.parameters);
    this.errorList.push({
        message:d,
        element:a
    });
    this.errorMap[a.name]=d;
    this.submitted[a.name]=d
    },
addWrapper:function(a){
    if(this.settings.wrapper)a=a.add(a.parent(this.settings.wrapper));
    return a
    },
defaultShowErrors:function(){
    for(var a=0;this.errorList[a];a++){
        var b=this.errorList[a];
        this.settings.highlight&&this.settings.highlight.call(this,b.element,this.settings.errorClass,this.settings.validClass);
        this.showLabel(b.element,b.message)
        }
        if(this.errorList.length)this.toShow=this.toShow.add(this.containers);
    if(this.settings.success)for(a=0;this.successList[a];a++)this.showLabel(this.successList[a]);
    if(this.settings.unhighlight){
        a=0;
        for(b=this.validElements();b[a];a++)this.settings.unhighlight.call(this,b[a],this.settings.errorClass,this.settings.validClass)
            }
            this.toHide=this.toHide.not(this.toShow);
    this.hideErrors();
    this.addWrapper(this.toShow).show()
    },
validElements:function(){
    return this.currentElements.not(this.invalidElements())
    },
invalidElements:function(){
    return c(this.errorList).map(function(){
        return this.element
        })
    },
showLabel:function(a,b){
    var d=this.errorsFor(a);
    if(d.length){
        d.removeClass().addClass(this.settings.errorClass);
        d.attr("generated")&&d.html(b)
        }else{
        d=c("<"+this.settings.errorElement+"/>").attr({
            "for":this.idOrName(a),
            generated:true
        }).addClass(this.settings.errorClass).html(b||
            "");
        if(this.settings.wrapper)d=d.hide().show().wrap("<"+this.settings.wrapper+"/>").parent();
        this.labelContainer.append(d).length||(this.settings.errorPlacement?this.settings.errorPlacement(d,c(a)):d.insertAfter(a))
        }
        if(!b&&this.settings.success){
        d.text("");
        typeof this.settings.success=="string"?d.addClass(this.settings.success):this.settings.success(d)
        }
        this.toShow=this.toShow.add(d)
    },
errorsFor:function(a){
    var b=this.idOrName(a);
    return this.errors().filter(function(){
        return c(this).attr("for")==b
        })
    },
idOrName:function(a){
    return this.groups[a.name]||(this.checkable(a)?a.name:a.id||a.name)
    },
checkable:function(a){
    return/radio|checkbox/i.test(a.type)
    },
findByName:function(a){
    var b=this.currentForm;
    return c(document.getElementsByName(a)).map(function(d,e){
        return e.form==b&&e.name==a&&e||null
        })
    },
getLength:function(a,b){
    switch(b.nodeName.toLowerCase()){
        case "select":
            return c("option:selected",b).length;
        case "input":
            if(this.checkable(b))return this.findByName(b.name).filter(":checked").length
            }
            return a.length
    },
depend:function(a,b){
    return this.dependTypes[typeof a]?this.dependTypes[typeof a](a,b):true
    },
dependTypes:{
    "boolean":function(a){
        return a
        },
    string:function(a,b){
        return!!c(a,b.form).length
        },
    "function":function(a,b){
        return a(b)
        }
    },
optional:function(a){
    return!c.validator.methods.required.call(this,c.trim(a.value),a)&&"dependency-mismatch"
    },
startRequest:function(a){
    if(!this.pending[a.name]){
        this.pendingRequest++;
        this.pending[a.name]=true
        }
    },
stopRequest:function(a,b){
    this.pendingRequest--;
    if(this.pendingRequest<
        0)this.pendingRequest=0;
    delete this.pending[a.name];
    if(b&&this.pendingRequest==0&&this.formSubmitted&&this.form()){
        c(this.currentForm).submit();
        this.formSubmitted=false
        }else if(!b&&this.pendingRequest==0&&this.formSubmitted){
        c(this.currentForm).triggerHandler("invalid-form",[this]);
        this.formSubmitted=false
        }
    },
previousValue:function(a){
    return c.data(a,"previousValue")||c.data(a,"previousValue",{
        old:null,
        valid:true,
        message:this.defaultMessage(a,"remote")
        })
    }
},
classRuleSettings:{
    required:{
        required:true
    },
    email:{
        email:true
    },
    url:{
        url:true
    },
    date:{
        date:true
    },
    dateISO:{
        dateISO:true
    },
    dateDE:{
        dateDE:true
    },
    number:{
        number:true
    },
    numberDE:{
        numberDE:true
    },
    digits:{
        digits:true
    },
    creditcard:{
        creditcard:true
    }
},
addClassRules:function(a,b){
    a.constructor==String?this.classRuleSettings[a]=b:c.extend(this.classRuleSettings,a)
    },
classRules:function(a){
    var b={
    };
    (a=c(a).attr("class"))&&c.each(a.split(" "),function(){
        this in c.validator.classRuleSettings&&c.extend(b,c.validator.classRuleSettings[this])
        });
    return b
    },
attributeRules:function(a){
    var b=
    {
    };
    
    a=c(a);
    for(var d in c.validator.methods){
        var e=a.attr(d);
        if(e)b[d]=e
            }
            b.maxlength&&/-1|2147483647|524288/.test(b.maxlength)&&delete b.maxlength;
    return b
    },
metadataRules:function(a){
    if(!c.metadata)return{
        };
        
    var b=c.data(a.form,"validator").settings.meta;
    return b?c(a).metadata()[b]:c(a).metadata()
    },
staticRules:function(a){
    var b={
    },d=c.data(a.form,"validator");
    if(d.settings.rules)b=c.validator.normalizeRule(d.settings.rules[a.name])||{
        };
        
    return b
    },
normalizeRules:function(a,b){
    c.each(a,function(d,e){
        if(e===
            false)delete a[d];
        else if(e.param||e.depends){
            var f=true;
            switch(typeof e.depends){
                case "string":
                    f=!!c(e.depends,b.form).length;
                    break;
                case "function":
                    f=e.depends.call(b,b)
                    }
                    if(f)a[d]=e.param!==undefined?e.param:true;else delete a[d]
        }
    });
c.each(a,function(d,e){
    a[d]=c.isFunction(e)?e(b):e
    });
c.each(["minlength","maxlength","min","max"],function(){
    if(a[this])a[this]=Number(a[this])
        });
c.each(["rangelength","range"],function(){
    if(a[this])a[this]=[Number(a[this][0]),Number(a[this][1])]
        });
if(c.validator.autoCreateRanges){
    if(a.min&&
        a.max){
        a.range=[a.min,a.max];
        delete a.min;
        delete a.max
        }
        if(a.minlength&&a.maxlength){
        a.rangelength=[a.minlength,a.maxlength];
        delete a.minlength;
        delete a.maxlength
        }
    }
a.messages&&delete a.messages;
return a
},
normalizeRule:function(a){
    if(typeof a=="string"){
        var b={
        };
        
        c.each(a.split(/\s/),function(){
            b[this]=true
            });
        a=b
        }
        return a
    },
addMethod:function(a,b,d){
    c.validator.methods[a]=b;
    c.validator.messages[a]=d!=undefined?d:c.validator.messages[a];
    b.length<3&&c.validator.addClassRules(a,c.validator.normalizeRule(a))
    },
methods:{
    required:function(a,b,d){
        if(!this.depend(d,b))return"dependency-mismatch";
        switch(b.nodeName.toLowerCase()){
            case "select":
                return(a=c(b).val())&&a.length>0;
            case "input":
                if(this.checkable(b))return this.getLength(a,b)>0;default:
                return c.trim(a).length>0
                }
            },
remote:function(a,b,d){
    if(this.optional(b))return"dependency-mismatch";
    var e=this.previousValue(b);
    this.settings.messages[b.name]||(this.settings.messages[b.name]={
        });
    e.originalMessage=this.settings.messages[b.name].remote;
    this.settings.messages[b.name].remote=
    e.message;
    d=typeof d=="string"&&{
        url:d
    }||d;
    if(this.pending[b.name])return"pending";
    if(e.old===a)return e.valid;
    e.old=a;
    var f=this;
    this.startRequest(b);
    var g={
    };
    
    g[b.name]=a;
    c.ajax(c.extend(true,{
        url:d,
        mode:"abort",
        port:"validate"+b.name,
        dataType:"json",
        data:g,
        success:function(h){
            f.settings.messages[b.name].remote=e.originalMessage;
            var j=h===true;
            if(j){
                var i=f.formSubmitted;
                f.prepareElement(b);
                f.formSubmitted=i;
                f.successList.push(b);
                f.showErrors()
                }else{
                i={
                };
                
                h=h||f.defaultMessage(b,"remote");
                i[b.name]=
                e.message=c.isFunction(h)?h(a):h;
                f.showErrors(i)
                }
                e.valid=j;
            f.stopRequest(b,j)
            }
        },d));
return"pending"
},
minlength:function(a,b,d){
    return this.optional(b)||this.getLength(c.trim(a),b)>=d
    },
maxlength:function(a,b,d){
    return this.optional(b)||this.getLength(c.trim(a),b)<=d
    },
rangelength:function(a,b,d){
    a=this.getLength(c.trim(a),b);
    return this.optional(b)||a>=d[0]&&a<=d[1]
    },
min:function(a,b,d){
    return this.optional(b)||a>=d
    },
max:function(a,b,d){
    return this.optional(b)||a<=d
    },
range:function(a,b,d){
    return this.optional(b)||
    a>=d[0]&&a<=d[1]
    },
email:function(a,b){
    return this.optional(b)||/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(a)
    },
url:function(a,b){
    return this.optional(b)||/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(a)},
date:function(a,b){
    return this.optional(b)||!/Invalid|NaN/.test(new Date(a))
    },
dateISO:function(a,b){
    return this.optional(b)||/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(a)
    },
number:function(a,b){
    return this.optional(b)||/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(a)
    },
digits:function(a,b){
    return this.optional(b)||/^\d+$/.test(a)
    },
creditcard:function(a,b){
    if(this.optional(b))return"dependency-mismatch";
    if(/[^0-9-]+/.test(a))return false;
    var d=0,e=0,f=false;
    a=a.replace(/\D/g,"");
    for(var g=a.length-1;g>=
        0;g--){
        e=a.charAt(g);
        e=parseInt(e,10);
        if(f)if((e*=2)>9)e-=9;
        d+=e;
        f=!f
        }
        return d%10==0
    },
accept:function(a,b,d){
    d=typeof d=="string"?d.replace(/,/g,"|"):"png|jpe?g|gif";
    return this.optional(b)||a.match(RegExp(".("+d+")$","i"))
    },
equalTo:function(a,b,d){
    d=c(d).unbind(".validate-equalTo").bind("blur.validate-equalTo",function(){
        c(b).valid()
        });
    return a==d.val()
    }
}
});
c.format=c.validator.format
})(jQuery);
(function(c){
    var a={
    };
    
    if(c.ajaxPrefilter)c.ajaxPrefilter(function(d,e,f){
        e=d.port;
        if(d.mode=="abort"){
            a[e]&&a[e].abort();
            a[e]=f
            }
        });
else{
    var b=c.ajax;
    c.ajax=function(d){
        var e=("port"in d?d:c.ajaxSettings).port;
        if(("mode"in d?d:c.ajaxSettings).mode=="abort"){
            a[e]&&a[e].abort();
            return a[e]=b.apply(this,arguments)
            }
            return b.apply(this,arguments)
        }
    }
})(jQuery);
(function(c){
    !jQuery.event.special.focusin&&!jQuery.event.special.focusout&&document.addEventListener&&c.each({
        focus:"focusin",
        blur:"focusout"
    },function(a,b){
        function d(e){
            e=c.event.fix(e);
            e.type=b;
            return c.event.handle.call(this,e)
            }
            c.event.special[b]={
            setup:function(){
                this.addEventListener(a,d,true)
                },
            teardown:function(){
                this.removeEventListener(a,d,true)
                },
            handler:function(e){
                arguments[0]=c.event.fix(e);
                arguments[0].type=b;
                return c.event.handle.apply(this,arguments)
                }
            }
    });
c.extend(c.fn,{
    validateDelegate:function(a,
        b,d){
        return this.bind(b,function(e){
            var f=c(e.target);
            if(f.is(a))return d.apply(f,arguments)
                })
        }
    })
})(jQuery);
(function(a){
    a.fn.visualize=function(c,b){
        return a(this).each(function(){
            var s=a.extend({
                type:"bar",
                width:a(this).width(),
                height:a(this).height(),
                appendTitle:true,
                title:null,
                appendKey:true,
                rowFilter:" ",
                colFilter:" ",
                colors:["#be1e2d","#666699","#92d5ea","#ee8310","#8d10ee","#5a3b16","#26a4ed","#f45a90","#e9e744"],
                textColors:[],
                parseDirection:"x",
                pieMargin:20,
                pieLabelsAsPercent:true,
                pieLabelPos:"inside",
                lineWeight:4,
                barGroupMargin:10,
                barMargin:1,
                yLabelInterval:30
            },c);
            s.width=parseFloat(s.width);
            s.height=parseFloat(s.height);
            var r=a(this);
            function A(){
                var o=s.colors;
                var C=s.textColors;
                var B={
                    dataGroups:function(){
                        var D=[];
                        if(s.parseDirection=="x"){
                            r.find("tr:gt(0)").filter(s.rowFilter).each(function(G){
                                D[G]={
                                };
                                
                                D[G].points=[];
                                D[G].color=o[G];
                                if(C[G]){
                                    D[G].textColor=C[G]
                                    }
                                    a(this).find("td").filter(s.colFilter).each(function(){
                                    D[G].points.push(parseFloat(a(this).text()))
                                    })
                                })
                            }else{
                            var F=r.find("tr:eq(1) td").filter(s.colFilter).size();
                            for(var E=0;E<F;E++){
                                D[E]={
                                };
                                
                                D[E].points=[];
                                D[E].color=o[E];
                                if(C[E]){
                                    D[E].textColor=C[E]
                                    }
                                    r.find("tr:gt(0)").filter(s.rowFilter).each(function(){
                                    D[E].points.push(a(this).find("td").filter(s.colFilter).eq(E).text()*1)
                                    })
                                }
                            }
                            return D
                    },
                allData:function(){
                    var D=[];
                    a(this.dataGroups()).each(function(){
                        D.push(this.points)
                        });
                    return D
                    },
                dataSum:function(){
                    var E=0;
                    var D=this.allData().join(",").split(",");
                    a(D).each(function(){
                        E+=parseFloat(this)
                        });
                    return E
                    },
                topValue:function(){
                    var E=0;
                    var D=this.allData().join(",").split(",");
                    a(D).each(function(){
                        if(parseFloat(this,10)>E){
                            E=parseFloat(this)
                            }
                        });
                return E
                },
            bottomValue:function(){
                var E=0;
                var D=this.allData().join(",").split(",");
                a(D).each(function(){
                    if(this<E){
                        E=parseFloat(this)
                        }
                    });
            return E
            },
        memberTotals:function(){
            var E=[];
            var D=this.dataGroups();
            a(D).each(function(F){
                var G=0;
                a(D[F].points).each(function(H){
                    G+=D[F].points[H]
                    });
                E.push(G)
                });
            return E
            },
        yTotals:function(){
            var G=[];
            var E=this.dataGroups();
            var H=this.xLabels().length;
            for(var F=0;F<H;F++){
                G[F]=[];
                var D=0;
                a(E).each(function(I){
                    G[F].push(this.points[F])
                    });
                G[F].join(",").split(",");
                a(G[F]).each(function(){
                    D+=parseFloat(this)
                    });
                G[F]=D
                }
                return G
            },
        topYtotal:function(){
            var D=0;
            var E=this.yTotals().join(",").split(",");
            a(E).each(function(){
                if(parseFloat(this,10)>D){
                    D=parseFloat(this)
                    }
                });
        return D
        },
        totalYRange:function(){
            return this.topValue()-this.bottomValue()
            },
        xLabels:function(){
            var D=[];
            if(s.parseDirection=="x"){
                r.find("tr:eq(0) th").filter(s.colFilter).each(function(){
                    D.push(a(this).html())
                    })
                }else{
                r.find("tr:gt(0) th").filter(s.rowFilter).each(function(){
                    D.push(a(this).html())
                    })
                }
                return D
            },
        yLabels:function(){
            var F=[];
            F.push(j);
            var E=Math.round(s.height/s.yLabelInterval);
            var D=Math.ceil(d/E)||1;
            while(F[F.length-1]<p-D){
                F.push(F[F.length-1]+D)
                }
                F.push(p);
            return F
            }
        };
    
return B
}
var x={
    pie:function(){
        l.addClass("visualize-pie");
        if(s.pieLabelPos=="outside"){
            l.addClass("visualize-pie-outside")
            }
            var E=Math.round(h.width()/2);
        var D=Math.round(h.height()/2);
        var o=D-s.pieMargin;
        var B=0;
        var C=function(G){
            return(Math.PI/180)*G
            };
            
        var F=a('<ul class="visualize-labels"></ul>').insertAfter(h);
        a.each(n,function(L){
            var Q=(this<=0||isNaN(this))?0:this/m;
            t.beginPath();
            t.moveTo(E,D);
            t.arc(E,D,o,B*Math.PI*2-Math.PI*0.5,(B+Q)*Math.PI*2-Math.PI*0.5,false);
            t.lineTo(E,D);
            t.closePath();
            t.fillStyle=e[L].color;
            t.fill();
            var O=(B+Q/2);
            var G=s.pieLabelPos=="inside"?o/1.5:o+o/5;
            var K=Math.round(E+Math.sin(O*Math.PI*2)*(G));
            var J=Math.round(D-Math.cos(O*Math.PI*2)*(G));
            var H=(K>E)?"right":"left";
            var I=(J>D)?"bottom":"top";
            var R=parseFloat((Q*100).toFixed(2));
            if(R){
                var M=(s.pieLabelsAsPercent)?R+"%":this;
                var P=a('<span class="visualize-label">'+M+"</span>").css(H,0).css(I,0);
                if(P){
                    var N=a('<li class="visualize-label-pos"></li>').appendTo(F).css({
                        left:K,
                        top:J
                    }).append(P)
                    }
                    P.css("font-size",o/8).css("margin-"+H,-P.width()/2).css("margin-"+I,-P.outerHeight()/2);
                if(e[L].textColor){
                    P.css("color",e[L].textColor)
                    }
                }
            B+=Q
        })
    },
line:function(E){
    if(E){
        l.addClass("visualize-area")
        }else{
        l.addClass("visualize-line")
        }
        var F=h.width()/(q.length-1);
    var C=a('<ul class="visualize-labels-x"></ul>').width(h.width()).height(h.height()).insertBefore(h);
    a.each(q,function(I){
        var G=a("<li><span>"+this+"</span></li>").prepend('<span class="line" />').css("left",F*I).appendTo(C);
        var H=G.find("span:not(.line)");
        var J=H.width()/-2;
        if(I==0){
            J=0
            }else{
            if(I==q.length-1){
                J=-H.width()
                }
            }
        H.css("margin-left",J).addClass("label")
        });
var D=h.height()/d;
    var o=h.height()/(y.length-1);
    var B=a('<ul class="visualize-labels-y"></ul>').width(h.width()).height(h.height()).insertBefore(h);
    a.each(y,function(J){
    var G=a("<li><span>"+this+"</span></li>").prepend('<span class="line"  />').css("bottom",o*J).prependTo(B);
    var H=G.find("span:not(.line)");
    var I=H.height()/-2;
    if(J==0){
        I=-H.height()
        }else{
        if(J==y.length-1){
            I=0
            }
        }
    H.css("margin-top",I).addClass("label")
    });
t.translate(0,f);
a.each(e,function(I){
    t.beginPath();
    t.lineWidth=s.lineWeight;
    t.lineJoin="round";
    var H=this.points;
    var G=0;
    t.moveTo(0,-(H[0]*D));
    a.each(H,function(){
        t.lineTo(G,-(this*D));
        G+=F
        });
    t.strokeStyle=this.color;
    t.stroke();
    if(E){
        t.lineTo(G,0);
        t.lineTo(0,0);
        t.closePath();
        t.fillStyle=this.color;
        t.globalAlpha=0.3;
        t.fill();
        t.globalAlpha=1
        }else{
        t.closePath()
        }
    })
},
area:function(){
    x.line(true)
    },
bar:function(){
    l.addClass("visualize-bar");
    var I=h.width()/(q.length);
    var E=a('<ul class="visualize-labels-x"></ul>').width(h.width()).height(h.height()).insertBefore(h);
    a.each(q,function(O){
        var M=a('<li><span class="label">'+this+"</span></li>").prepend('<span class="line" />').css("left",I*O).width(I).appendTo(E);
        var N=M.find("span.label");
        N.addClass("label")
        });
    var o=h.height()/d;
    var F=h.height()/(y.length-1);
    var K=a('<ul class="visualize-labels-y"></ul>').width(h.width()).height(h.height()).insertBefore(h);
    a.each(y,function(P){
        var M=a("<li><span>"+this+"</span></li>").prepend('<span class="line"  />').css("bottom",F*P).prependTo(K);
        var N=M.find("span:not(.line)");
        var O=N.height()/-2;
        if(P==0){
            O=-N.height()
            }else{
            if(P==y.length-1){
                O=0
                }
            }
        N.css("margin-top",O).addClass("label")
        });
t.translate(0,f);
for(var D=0;D<e.length;D++){
    t.beginPath();
    var G=(I-s.barGroupMargin*2)/e.length;
    var H=G-(s.barMargin*2);
    t.lineWidth=H;
    var J=e[D].points;
    var C=0;
    for(var B=0;B<J.length;B++){
        var L=(C-s.barGroupMargin)+(D*G)+G/2;
        L+=s.barGroupMargin*2;
        t.moveTo(L,0);
        t.lineTo(L,Math.round(-J[B]*o));
        C+=I
        }
        t.strokeStyle=e[D].color;
    t.stroke();
    t.closePath()
    }
}
};

var k=document.createElement("canvas");
k.setAttribute("height",s.height);
k.setAttribute("width",s.width);
var h=a(k);
var z=s.title||r.find("caption").text();
var l=(b||a('<div class="visualize" role="img" aria-label="Chart representing data from the table: '+z+'" />')).height(s.height).width(s.width).append(h);
var g=A();
var e=g.dataGroups();
var w=g.allData();
var m=g.dataSum();
var p=g.topValue();
var j=g.bottomValue();
var n=g.memberTotals();
var d=g.totalYRange();
var f=s.height*(p/d);
var q=g.xLabels();
var y=g.yLabels();
if(s.appendTitle||s.appendKey){
    var i=a('<div class="visualize-info"></div>').appendTo(l)
    }
    if(s.appendTitle){
    a('<div class="visualize-title">'+z+"</div>").appendTo(i)
    }
    if(s.appendKey){
    var v=a('<ul class="visualize-key"></ul>');
    var u;
    if(s.parseDirection=="x"){
        u=r.find("tr:gt(0) th").filter(s.rowFilter)
        }else{
        u=r.find("tr:eq(0) th").filter(s.colFilter)
        }
        u.each(function(o){
        a('<li><span class="visualize-key-color" style="background: '+e[o].color+'"></span><span class="visualize-key-label">'+a(this).text()+"</span></li>").appendTo(v)
        });
    v.appendTo(i)
    }
    if(!b){
    l.insertAfter(this)
    }
    if(typeof(G_vmlCanvasManager)!="undefined"){
    G_vmlCanvasManager.init();
    G_vmlCanvasManager.initElement(h[0])
    }
    var t=h[0].getContext("2d");
x[s.type]();
a(".visualize-line li:first-child span.line, .visualize-line li:last-child span.line, .visualize-area li:first-child span.line, .visualize-area li:last-child span.line, .visualize-bar li:first-child span.line,.visualize-bar .visualize-labels-y li:last-child span.line").css("border","none");
if(!b){
    l.bind("visualizeRefresh",function(){
        r.visualize(s,a(this).empty())
        })
    }
}).next()
}
})(jQuery);
jQuery.fn.initMenu=function(){
    return this.each(function(){
        var a=$(this).get(0);
        $("li:has(ul)",this).each(function(){
            $(">a",this).append("<span class='arrow'></span>")
            });
        $(".sub",this).hide();
        $("li.expand > .sub",this).show();
        $("li.expand > .sub",this).prev().addClass("active");
        $("li a",this).click(function(d){
            d.stopImmediatePropagation();
            var c=$(this).next();
            var b=this.parentNode.parentNode;
            if($(this).hasClass("active-icon")){
                $(this).addClass("non-active-icon");
                $(this).removeClass("active-icon")
                }else{
                $(this).addClass("active-icon");
                $(this).removeClass("non-active-icon")
                }
                if($(b).hasClass("noaccordion")){
                if(c[0]===undefined){
                    window.location.href=this.href
                    }
                    $(c).slideToggle("normal",function(){
                    if($(this).is(":visible")){
                        $(this).prev().addClass("active")
                        }else{
                        $(this).prev().removeClass("active");
                        $(this).prev().removeClass("active-icon")
                        }
                    });
            return false
            }else{
            if(c.hasClass("sub")&&c.is(":visible")){
                if($(b).hasClass("collapsible")){
                    $(".sub:visible",b).first().slideUp("normal",function(){
                        $(this).prev().removeClass("active");
                        $(this).prev().removeClass("active-icon")
                        });
                    return false
                    }
                    return false
                }
                if(c.hasClass("sub")&&!c.is(":visible")){
                $(".sub:visible",b).first().slideUp("normal",function(){
                    $(this).prev().removeClass("active");
                    $(this).prev().removeClass("active-icon")
                    });
                c.slideDown("normal",function(){
                    $(this).prev().addClass("active")
                    });
                return false
                }
            }
        })
})
};
(function(a){
    a.fn.slideList=function(b){
        return a(this).each(function(){
            var d=a(this).css("padding-left");
            var c=a(this).css("padding-right");
            a(this).hover(function(){
                a(this).animate({
                    paddingLeft:parseInt(d)+parseInt(5)+"px"
                    },130)
                },function(){
                bc_hover=a(this).css("background-color");
                a(this).animate({
                    paddingLeft:d,
                    paddingRight:c
                },130)
                })
            })
        }
    })(jQuery);
(function(a){
    a.fn.alertBox=function(d,b){
        var c=a.extend({
            },a.fn.alertBox.defaults,b);
        this.each(function(f){
            var j=a(this);
            var e="alert "+c.type;
            if(c.noMargin){
                e+=" no-margin"
                }
                if(c.position){
                e+=" top"
                }
                var h='<div id="alertBox-generated" style="display:none" class="'+e+'">'+d+"</div>";
            var g=j.prepend(h);
            a("#alertBox-generated").fadeIn()
            })
        };
        
    a.fn.alertBox.defaults={
        type:"info",
        position:"top",
        noMargin:true
    }
})(jQuery);
(function(a){
    a.fn.removeAlertBoxes=function(d,c){
        var e=a(this);
        var b=e.find(".alert");
        b.remove()
        }
    })(jQuery);
$("[placeholder]").focus(function(){
    var a=$(this);
    if(a.val()==a.attr("placeholder")){
        a.val("");
        a.removeClass("placeholder")
        }
    }).blur(function(){
    var a=$(this);
    if(a.val()==""||a.val()==a.attr("placeholder")){
        a.addClass("placeholder");
        a.val(a.attr("placeholder"))
        }
    }).blur().parents("form").submit(function(){
    $(this).find("[placeholder]").each(function(){
        var a=$(this);
        if(a.val()==a.attr("placeholder")){
            a.val("")
            }
        })
});
$.fn.resetForm=function(){
    $(this).removeAlertBoxes();
    return this.each(function(){
        if(typeof this.reset=="function"||(typeof this.reset=="object"&&!this.reset.nodeType)){
            this.reset()
            }
        })
};
(function(a){
    a.fn.createTabs=function(){
        var b=a(this);
        b.find(".tab-content").hide();
        b.find("ul.tabs li:first").addClass("active").show();
        b.find(".tab-content:first").show();
        b.find("ul.tabs li").click(function(){
            b.find("ul.tabs li").removeClass("active");
            a(this).addClass("active");
            b.find(".tab-content").hide();
            var c=a(this).find("a").attr("href");
            a(c).fadeIn();
            return false
            })
        }
    })(jQuery);
window.log=function(){
    log.history=log.history||[];
    log.history.push(arguments);
    if(this.console){
        arguments.callee=arguments.callee.caller;
        var a=[].slice.call(arguments);
        (typeof console.log==="object"?log.apply.call(console.log,console,a):console.log.apply(console,a))
        }
    };
(function(e){
    function h(){
    }
    for(var g="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,timeStamp,profile,profileEnd,time,timeEnd,trace,warn".split(","),f;f=g.pop();){
        e[f]=e[f]||h
        }
    })((function(){
    try{
        console.log();
        return window.console
        }catch(a){
        return window.console={
        }
    }
})());
$(document).ready(function(){
    $(".menu").initMenu();
    $(".menu li a").slideList();
    $("a[href*=#]").bind("click",function(b){
        b.preventDefault();
        var c=$(this).attr("href");
        if(c=="#top"){
            $.jGrowl("Rolagem para o topo.",{theme:"information"})
        }
        $("html,body").animate({scrollTop:$(c).offset().top},1000,function(){})
        });
    $("span.hide").click(function(){
        $(this).parent().slideUp()
        });
    $(".toolbox-action").click(function(){
        $(".toolbox-content").fadeOut();
        $(this).next().fadeIn();
        return false
        });
    $(".close-toolbox").click(function(){
        $(this).parents(".toolbox-content").fadeOut()
        });
    $(".user-button").click(function(){
        $(".dropdown-username-menu").slideToggle()
        });
    $(document).click(function(b){
        if(!$(b.target).is(".user-button, .arrow-link-down, .dropdown-username-menu *")){
            $(".dropdown-username-menu").slideUp()
            }
        });
var a;
$(".user-button, ul.dropdown-username-menu").mouseleave(function(b){
    a=setTimeout(function(){
        $(".dropdown-username-menu").slideUp()
        },400)
    });
$(".user-button, ul.dropdown-username-menu").mouseenter(function(b){
    clearTimeout(a)
    });
$(".block-border .block-header span").click(function(){
    if($(this).hasClass("closed")){
        $(this).removeClass("closed")
        }else{
        $(this).addClass("closed")
        }
        $(this).parent().parent().children(".block-content").slideToggle()
    });
$("a[rel=tooltip]").tipsy({
    fade:true
});
$("a[rel=tooltip-bottom]").tipsy({
    fade:true
});
$("a[rel=tooltip-right]").tipsy({
    fade:true,
    gravity:"w"
});
$("a[rel=tooltip-top]").tipsy({
    fade:true,
    gravity:"s"
});
$("a[rel=tooltip-left]").tipsy({
    fade:true,
    gravity:"e"
});
$("a[rel=tooltip-html]").tipsy({
    fade:true,
    html:true
});
$("div[rel=tooltip]").tipsy({
    fade:true
})
});

$().ready(function() {
    /*$.validator.setDefaults({

        submitHandler: function(e) {

            $.jGrowl("Form was successfully submitted.", {
                theme: 'success'
            });
            $(e).parent().parent().fadeOut();
            v.resetForm();
            v2.resetForm();
            v3.resetForm();
        }
    });*/
    /*
    var v = $("#create-user-form").validate();
    jQuery("#reset").click(function() {
        v.resetForm();
        $.jGrowl("User was not created!", {
            theme: 'error'
        });
    });
    var v2 = $("#write-message-form").validate();
    jQuery("#reset2").click(function() {
        v2.resetForm();
        $.jGrowl("Message was not sent.", {
            theme: 'error'
        });
    });
    var v3 = $("#create-folder-form").validate();
    jQuery("#reset3").click(function() {
        v3.resetForm();
        $.jGrowl("Folder was not created!", {
            theme: 'error'
        });
    });
    //var validateform = $("#validate-form").validate();
    $("#reset-validate-form").click(function() {

        validateform.resetForm();
        $.jGrowl("Blogpost was not created.", {
            theme: 'error'
        });
    });
    /*var validatelogin = $("#login-form").validate({

        invalidHandler: function(form, validator) {

            var errors = validator.numberOfInvalids();
            if (errors) {

                var message = errors == 1
                ? 'You missed 1 field. It has been highlighted.'
                : 'You missed ' + errors + ' fields. They have been highlighted.';
                $('#login-form').removeAlertBoxes();
                $('#login-form').alertBox(message, {
                    type: 'error'
                });
        			
            } else {

                $('#login-form').removeAlertBoxes();
            }
        }
    });
    jQuery("#reset-login").click(function() {

        validatelogin.resetForm();
    });
    $( "#datepicker" ).datepicker();
    $('#table-example').dataTable();
    $('#graph-data').visualize({
        type: 'line', 
        height: 250
    }).appendTo('#tab-line').trigger('visualizeRefresh');
    $('#graph-data').visualize({
        type: 'area', 
        height: 250
    }).appendTo('#tab-area').trigger('visualizeRefresh');
    $('#graph-data').visualize({
        type: 'pie', 
        height: 250
    }).appendTo('#tab-pie').trigger('visualizeRefresh');
    $('#graph-data').visualize({
        type: 'bar', 
        height: 250
    }).appendTo('#tab-bar').trigger('visualizeRefresh');
    $("#specify-a-unique-tab-name").createTabs();
    $("#tab-graph").createTabs();
    $("#tab-panel-1").createTabs();
    $("#tab-panel-2").createTabs();
    $('#slider').sliderNav();
    $('#notification-success').click(function() {

        $.jGrowl("Hey, I'm a <strong>success</strong> message. :-)<br>I want to say you something...", {
            theme: 'success'
        });
    });
    $('#notification-error').click(function() {

        $.jGrowl("Hey, I'm a <strong>error</strong> message. :-)<br>I want to say you something...", {
            theme: 'error'
        });
    });
    $('#notification-information').click(function() {

        $.jGrowl("Hey, I'm a <strong>information</strong> message. :-)<br>I want to say you something...", {
            theme: 'information'
        });
    });
    $('#notification-warning').click(function() {

        $.jGrowl("Hey, I'm a <strong>warning</strong> message. :-)<br>I want to say you something...", {
            theme: 'warning'
        });
    });
    $('#notification-saved').click(function() {

        $.jGrowl("Hey, I'm a <strong>saved</strong> message. :-)<br>I want to say you something...", {
            theme: 'saved'
        });
    });*/
    $("select.normal, input:checkbox.normal, input:text, input:password, input:radio, textarea").uniform();//, input:file, input:checkbox
});