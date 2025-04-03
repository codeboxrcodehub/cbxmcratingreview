import{_ as A,r as w,o as n,f as _,a as t,t as l,b as y,e as m,j as x,w as L,n as O,d as j,g as h,h as N,F as f,x as R,v as K,z as Y,c as G,A as Z,B as $,C as X,D as ee,E as te,H as se,I as oe,G as le}from"./vue-router.js";import{W as ie,T as ae,d as re,v as ne,Q as ce}from"./cbxmcratingreview-head.js";import{C as _e}from"./component.js";import{W as ue}from"./cbxmcratingreview-head-alt.js";import{S as de,F as me}from"./FileUpload.js";import{d as D,F as be,a as he,E as ge}from"./vee-validate.js";const ve={name:"Logs",components:{WpHead:ie,TableLite:ae,flatPickr:_e},async mounted(){this.$route.query.order_by&&this.$route.query.sort&&(this.table.sortable.order=this.$route.query.order_by,this.table.sortable.sort=this.$route.query.sort,this.order_by(this.$route.query.order_by,this.$route.query.sort)),this.$route.query.page&&await this.pagination(this.$route.query.page);let o=[],s=await localStorage.getItem("cbxmcratingreview_front_log_list");s&&(s=JSON.parse(s),Array.isArray(s)&&s.length&&(o=s)),Array.isArray(o)&&o.length===0&&(o=["form","post","user","review","headline","status","date_created","action"]),this.table_col_show=o,this.table.columns=await this.set_table_columns(),await this.doSearch(0,this.limit,this.table.sortable.order,this.table.sortable.sort)},watch:{table_col_show:{deep:!0,handler(o){localStorage.setItem("cbxmcratingreview_front_log_list",JSON.stringify(o)),this.table.columns=this.set_table_columns()}}},data(){return{cbxmcratingreview_vue_var,cbx_table_lite:cbxmcratingreview_vue_var.cbx_table_lite,logs:[],table_col_show:[],dataLoading:!1,table:{isLoading:!1,columns:[],rows:[],sortable:{order:"id",sort:"desc"}},total_count:0,review_statuses:cbxmcratingreview_vue_var.review_statuses}},methods:{async doSearch(o,s,u,v,e=1){this.table.isLoading=!0,this.limit=s,this.dataLoading=!0,this.table.sortable.order=u,this.table.sortable.sort=v,await this.pagination(e),await this.order_by(this.table.sortable.order,this.table.sortable.sort),this.$router.push("/?"+this.queryStr),await this.axios.get(this.cbxmcratingreview_vue_var.rest_end_points.get_user_log_list+"?"+this.queryStr).then(i=>{var g,k,p,a,T,S,V,U,F,M,C,q,E;(g=i==null?void 0:i.data)!=null&&g.success?(this.total_count=(p=(k=i==null?void 0:i.data)==null?void 0:k.data)==null?void 0:p.total,this.table.rows=(T=(a=i==null?void 0:i.data)==null?void 0:a.data)==null?void 0:T.data,this.logs=(V=(S=i==null?void 0:i.data)==null?void 0:S.data)==null?void 0:V.data,(F=(U=i==null?void 0:i.data)==null?void 0:U.data)==null||delete F.data,this.paginationInfo=(M=i==null?void 0:i.data)==null?void 0:M.data,this.table.totalRecordCount=(C=this.paginationInfo)==null?void 0:C.total):((q=i==null?void 0:i.data)!=null&&q.info&&this.errorMessage((E=i==null?void 0:i.data)==null?void 0:E.info),this.paginationInfo={},this.table.rows=[]),this.table.isLoading=!1,this.dataLoading=!1})},pagination(o){let s=new URLSearchParams(this.queryStr);o?s.set("page",o):s.delete("page"),this.limit?s.set("limit",this.limit):s.delete("limit"),this.queryStr=s.toString()},async order_by(o,s){let u=new URLSearchParams(this.queryStr);this.queryStr=u.toString(),o?u.set("order_by",o):u.delete("order_by"),s?u.set("sort",s):u.delete("sort"),this.queryStr=u.toString()},set_table_columns(){let o=this.table_col_show;return[{label:cbxmcratingreview_vue_var.translations.post,field:"post",sortable:!1,is_show:o.includes("post")},{label:cbxmcratingreview_vue_var.translations.review,field:"score",sortable:!1,is_show:o.includes("review")},{label:cbxmcratingreview_vue_var.translations.headline,field:"headline",sortable:!1,is_show:o.includes("headline")},{label:cbxmcratingreview_vue_var.translations.comment,field:"comment",sortable:!1,is_show:o.includes("comment")},{label:cbxmcratingreview_vue_var.translations.status,field:"status",sortable:!1,is_show:o.includes("status")},{label:cbxmcratingreview_vue_var.translations.action,field:"action",sortable:!1,is_show:o.includes("action")}]}}},pe={class:"inside cbxmcratingreview-frontend-manager-inside",id:"cbxmcratingreview-frontend-dashboard"},fe={class:"container"},we={class:"row"},xe={class:"col-12"},ye={class:"cbx-sub-heading-wrap",id:"dashlisting_toolbar"},ke={class:"cbx-sub-heading-l"},qe={class:"cbx-sub-heading cbx-sub-heading-log cbx-sub-heading-logs"},Le={class:"cbx-listing-table-prebox-wrap cbx-listing-table-prebox-wrap-form cbx-listing-table-prebox-wrap-loglist"},Se={class:"container"},Ve={class:"row"},Ue={class:"col col-12"},Fe={class:"cbx-listing-table-prebox-l pull-left"},Me={class:"cbx-listing-table-prebox-r pull-right"},Ce={class:"dropdown dropdown-menu ml-10"},Ee=["title","aria-label"],Ne={class:"cbx-icon"},Re={class:"sr-only"},Te={class:"card card-menu card-menu-right"},De={class:"mt-5"},Ie={class:"checkbox_field"},Ae={for:"post"},He={class:"mt-5"},Be={class:"checkbox_field"},ze={for:"review"},Pe={class:"mt-5"},We={class:"checkbox_field"},Qe={for:"headline"},Je={class:"mt-5"},Oe={class:"checkbox_field"},je={for:"comment"},Ge={class:"mt-5"},Xe={class:"checkbox_field"},Ke={for:"status"},Ye={class:"mt-5"},Ze={class:"checkbox_field"},$e={for:"action"},et={class:"container"},tt=["innerHTML"],st=["href"],ot={class:"cbx-icon"},lt={class:"button-label"},it={class:"sr-only"};function at(o,s,u,v,e,i){const g=w("inline-svg"),k=w("router-link"),p=w("table-lite");return n(),_("div",pe,[t("div",fe,[t("div",we,[t("div",xe,[t("div",ye,[t("div",ke,[t("h2",qe,l(e.cbxmcratingreview_vue_var.translations.logs),1)]),s[7]||(s[7]=t("div",{class:"cbx-sub-heading-r"},null,-1))])])])]),t("div",Le,[t("div",Se,[t("div",Ve,[t("div",Ue,[t("div",Fe,[t("h3",null,l(e.cbxmcratingreview_vue_var.translations.total)+": "+l(e.total_count),1)]),t("div",Me,[t("details",Ce,[t("summary",{class:"button outline primary icon icon-only",title:e.cbxmcratingreview_vue_var.translations.buttons.filter.title,"aria-label":e.cbxmcratingreview_vue_var.translations.buttons.filter.title},[t("i",Ne,[y(g,{src:e.cbxmcratingreview_vue_var.icons_url+"icon_funnel.svg"},null,8,["src"])]),t("span",Re,l(e.cbxmcratingreview_vue_var.translations.buttons.filter.sr_label),1)],8,Ee),t("div",Te,[t("ul",null,[t("li",De,[t("div",Ie,[m(t("input",{type:"checkbox",class:"magic-checkbox",id:"post","onUpdate:modelValue":s[0]||(s[0]=a=>e.table_col_show=a),value:"post"},null,512),[[x,e.table_col_show]]),t("label",Ae,l(e.cbxmcratingreview_vue_var.translations.post),1)])]),t("li",He,[t("div",Be,[m(t("input",{type:"checkbox",class:"magic-checkbox",id:"review","onUpdate:modelValue":s[1]||(s[1]=a=>e.table_col_show=a),value:"review"},null,512),[[x,e.table_col_show]]),t("label",ze,l(e.cbxmcratingreview_vue_var.translations.review),1)])]),t("li",Pe,[t("div",We,[m(t("input",{type:"checkbox",class:"magic-checkbox",id:"headline","onUpdate:modelValue":s[2]||(s[2]=a=>e.table_col_show=a),value:"headline"},null,512),[[x,e.table_col_show]]),t("label",Qe,l(e.cbxmcratingreview_vue_var.translations.headline),1)])]),t("li",Je,[t("div",Oe,[m(t("input",{type:"checkbox",class:"magic-checkbox",id:"comment","onUpdate:modelValue":s[3]||(s[3]=a=>e.table_col_show=a),value:"comment"},null,512),[[x,e.table_col_show]]),t("label",je,l(e.cbxmcratingreview_vue_var.translations.comment),1)])]),t("li",Ge,[t("div",Xe,[m(t("input",{type:"checkbox",class:"magic-checkbox",id:"status","onUpdate:modelValue":s[4]||(s[4]=a=>e.table_col_show=a),value:"status"},null,512),[[x,e.table_col_show]]),t("label",Ke,l(e.cbxmcratingreview_vue_var.translations.status),1)])]),t("li",Ye,[t("div",Ze,[m(t("input",{type:"checkbox",class:"magic-checkbox",id:"action","onUpdate:modelValue":s[5]||(s[5]=a=>e.table_col_show=a),value:"action",disabled:""},null,512),[[x,e.table_col_show]]),t("label",$e,l(e.cbxmcratingreview_vue_var.translations.action),1)])])])])])])])])])]),t("div",et,[y(p,{translations:e.cbx_table_lite,messages:{pagingInfo:e.cbxmcratingreview_vue_var.translations.showing+" {0}-{1} "+e.cbxmcratingreview_vue_var.translations.of+" {2}",pageSizeChangeLabel:e.cbxmcratingreview_vue_var.translations.rowCount,gotoPageLabel:"     "+e.cbxmcratingreview_vue_var.translations.goTo,noDataAvailable:e.cbxmcratingreview_vue_var.translations.no_log_found},class:"cbx-listing-table cbx-listing-table-form mt-0","is-slot-mode":!0,"is-loading":e.table.isLoading,columns:e.table.columns,rows:e.logs,total:e.table.totalRecordCount,sortable:e.table.sortable,"page-size":o.limit,onDoSearch:i.doSearch,onIsFinished:s[6]||(s[6]=a=>e.table.isLoading=!1)},{comment:L(a=>[t("div",{innerHTML:a.value.comment},null,8,tt)]),post:L(a=>[t("a",{href:a.value.permalink,target:"_blank"},l(a.value.post.post_title),9,st)]),status:L(a=>[t("span",null,l(e.review_statuses[a.value.status]?e.review_statuses[a.value.status]:""),1)]),action:L(a=>[y(k,{role:"button",to:"log/"+a.value.id,class:"button primary icon icon-only icon-inline small",title:e.cbxmcratingreview_vue_var.translations.buttons.edit.sr_label},{default:L(()=>[t("i",ot,[y(g,{src:e.cbxmcratingreview_vue_var.icons_url+"icon_edit.svg"},null,8,["src"])]),t("span",lt,l(e.cbxmcratingreview_vue_var.translations.view),1),t("span",it,l(e.cbxmcratingreview_vue_var.translations.buttons.view.sr_label),1)]),_:2},1032,["to","title"])]),_:1},8,["translations","messages","is-loading","columns","rows","total","sortable","page-size","onDoSearch"])])])}const rt=A(ve,[["render",at]]),nt={name:"LogIndex",components:{WpHeadAlt:ue,StarRating:de,Full:me},async mounted(){this.log_id&&await this.getLog()},data(){var o;return{cbxmcratingreview_vue_var,log_status:cbxmcratingreview_vue_var.log_status,log_id:Number((o=this.$route.params)!=null&&o.id?this.$route.params.id:0),statuses:cbxmcratingreview_vue_var.mail_statuses,page:0,log:{},translations:cbxmcratingreview_vue_var.translations,quill_editor_options:{toolbar:[["bold","italic","underline"],[{header:1},{header:2}],[{list:"ordered"},{list:"bullet"}],["clean"][{direction:"rtl"}]]},review_statuses:cbxmcratingreview_vue_var.review_statuses,ratings_stars:null,star:4,key:0,cbxmcratingreviewpro_active:cbxmcratingreview_vue_var.cbxmcratingreviewpro_active,half_rating:!!Number(cbxmcratingreview_vue_var.half_rating),submitted:!1}},methods:{refreshData(o){this.getLog(),this.key++},async updateLog(){const{valid:o}=await this.$refs.form.validate();if(o){this.log.ratings.ratings_stars=this.ratings_stars;let s=this.log;this.submitted=!0,await this.axios.post(cbxmcratingreview_vue_var.rest_end_points.save_user_log,s).then(u=>{if(u.data.success)this.successMessage(u.data.info);else{let v=u.data.info;typeof u.data.error<"u"&&u.data.error&&(v+=u.data.error),this.errorMessage(v)}}).catch(u=>{this.errorMessage(u)}),this.submitted=!1}},getLog(){this.axios.get(this.cbxmcratingreview_vue_var.rest_end_points.get_user_log+"?id="+this.log_id).then(o=>{var s,u,v,e,i,g;if((s=o==null?void 0:o.data)!=null&&s.error){this.errorMessage((u=o==null?void 0:o.data)==null?void 0:u.error),this.$router.push("/");return}this.log=o.data,this.ratings_stars=(e=(v=this.log)==null?void 0:v.ratings)==null?void 0:e.ratings_stars,(g=(i=this.log)==null?void 0:i.form)!=null&&g.custom_question&&this.log.form.custom_question.forEach((k,p)=>{this.log.questions||(this.log.questions=[]),this.log.questions[p]||(this.log.questions[p]=[])})}).catch(o=>{this.errorMessage(o)})}}},ct={id:"cbxmcratingreview_builder_actions",class:"cbx_builder_actions cbxmcratingreview_builder_actions cbxmcratingreviewlog_builder_actions"},_t={class:"container"},ut={class:"cbxmcratingreview_builder_actions_wrap"},dt={class:"row"},mt={class:"col-8 is-vertical-align"},bt={class:"col-4 is-right"},ht={class:"cbxmcratingreview_log container"},gt={class:"cbxmcratingreview-edit-main"},vt={class:"widefat"},pt={key:0},ft={class:"alternate"},wt={class:"row-title"},xt={for:"tablecell"},yt={class:"row-title"},kt={for:"tablecell"},qt={target:"_blank"},Lt={key:0,class:"alternate"},St={class:"row-title"},Vt={for:"tablecell"},Ut={class:"alternate"},Ft={class:"row-title"},Mt={for:"tablecell"},Ct={target:"_blank"},Et={class:"cbxmcratingreview-form-fields"},Nt={class:"form-group mt-15"},Rt={for:"headline"},Tt=["placeholder"],Dt={class:"form-group mt-15"},It={class:"form-group mt-15"},At={key:0,class:"cbxmcratingreview_review_custom_questions"},Ht={class:"form-group mt-15"},Bt=["id"],zt=["id","onUpdate:modelValue"],Pt=["for"],Wt=["id","onUpdate:modelValue","placeholder"],Qt=["id","onUpdate:modelValue","placeholder"],Jt=["id","onUpdate:modelValue","placeholder"],Ot=["onUpdate:modelValue"],jt=["value"],Gt=["id","value","name","checked","onUpdate:modelValue"],Xt=["for"],Kt=["id","onUpdate:modelValue"],Yt=["for"],Zt={key:0,class:"form-group mt-15"},$t=["placeholder"],es={class:"mt-20"};function ts(o,s,u,v,e,i){var S,V,U,F,M,C;const g=w("router-link"),k=w("star-rating"),p=w("QuillEditor"),a=w("full"),T=w("Form");return n(),_(f,null,[t("div",ct,[t("div",_t,[t("div",ut,[t("div",dt,[t("div",mt,[t("h2",null,l(e.translations.edit_review)+" : "+l(e.log_id),1)]),t("div",bt,[t("button",{class:O(["button primary ld-ext-right",e.submitted?"running":""]),onClick:s[0]||(s[0]=(...q)=>i.updateLog&&i.updateLog(...q))},[j(l(e.translations.update)+" ",1),s[4]||(s[4]=t("span",{class:"ld ld-spin ld-ring"},null,-1))],2),y(g,{to:"/",class:"button outline primary"},{default:L(()=>[j(l(e.translations.back),1)]),_:1})])])])])]),t("div",ht,[t("div",gt,[t("table",vt,[e.log?(n(),_("tbody",pt,[t("tr",ft,[t("td",wt,[t("label",xt,l(e.translations.created),1)]),t("td",null,l((S=e.log)==null?void 0:S.formatted_create_date),1)]),t("tr",null,[t("td",yt,[t("label",kt,l(e.translations.post),1)]),t("td",null,[t("a",qt,l((U=(V=e.log)==null?void 0:V.post)==null?void 0:U.post_title),1)])]),t("template",null,[e.log.mod_by?(n(),_("tr",Lt,[t("td",St,[t("label",Vt,l(e.translations.last_update),1)]),t("td",null,l((F=e.log)==null?void 0:F.formatted_update_date),1)])):h("",!0),t("tr",Ut,[t("td",Ft,[t("label",Mt,l(e.translations.last_update_by),1)]),t("td",null,[t("a",Ct,l((C=(M=e.log)==null?void 0:M.mod_user)==null?void 0:C.display_name),1)])])])])):h("",!0)]),y(T,{onSubmit:i.updateLog,ref:"form"},{default:L(()=>{var q,E,H,B,z,P,W,Q,J;return[t("div",Et,[t("div",Nt,[t("label",Rt,l(e.translations.headline),1),s[5]||(s[5]=t("br",null,null,-1)),m(t("input",{id:"headline",class:"form-control",type:"text","onUpdate:modelValue":s[1]||(s[1]=c=>e.log.headline=c),placeholder:e.translations.one_line_review},null,8,Tt),[[N,e.log.headline]])]),t("div",Dt,[t("label",null,l(e.translations.edit_your_review),1),s[7]||(s[7]=t("br",null,null,-1)),(n(!0),_(f,null,R((E=(q=e.log)==null?void 0:q.form)==null?void 0:E.custom_criteria,(c,r)=>(n(),_("div",null,[t("label",null,l(c.label),1),s[6]||(s[6]=t("br",null,null,-1)),y(k,{increment:e.half_rating?.5:1,rating:e.ratings_stars[c.criteria_id].score,"onUpdate:rating":d=>e.ratings_stars[c.criteria_id].score=d,"star-size":30},null,8,["increment","rating","onUpdate:rating"])]))),256))]),t("div",It,[t("label",null,l(e.translations.your_review),1),s[8]||(s[8]=t("br",null,null,-1)),y(p,{theme:"snow",content:e.log.comment,"onUpdate:content":s[2]||(s[2]=c=>e.log.comment=c),contentType:"html",style:{height:"250px"},toolbar:e.quill_editor_options,placeholder:e.translations.comment},null,8,["content","toolbar","placeholder"])])]),(z=(B=(H=e.log)==null?void 0:H.form)==null?void 0:B.extrafields)!=null&&z.enable_question?(n(),_("div",At,[t("h3",null,l(e.translations.questions_and_answers),1),t("div",Ht,[(n(!0),_(f,null,R((W=(P=e.log)==null?void 0:P.form)==null?void 0:W.custom_question,(c,r)=>(n(),_("div",{class:"cbxmcratingreview-form-field cbxmcratingreview_review_custom_question mt-20",id:"cbxmcratingreview_review_custom_question_"+r},[c.type==="checkbox"?m((n(),_("input",{key:0,type:"checkbox",id:"cbxmcratingreview_q_field_"+r,class:"magic-checkbox","onUpdate:modelValue":d=>e.log.questions[r]=d,"true-value":"1","false-value":"0"},null,8,zt)),[[x,e.log.questions[r]]]):h("",!0),t("label",{class:O(["cbxmcratingreview_q_field_label",c.type!=="checkbox"?"pt-10":""]),for:"cbxmcratingreview_q_field_"+r},l(c.title),11,Pt),c.type==="text"?m((n(),_("input",{key:1,id:"cbxmcratingreview_q_field_"+r,class:"form-control mt-5",type:"text","onUpdate:modelValue":d=>e.log.questions[r]=d,placeholder:c.placeholder},null,8,Wt)),[[N,e.log.questions[r]]]):h("",!0),c.type==="textarea"?m((n(),_("textarea",{key:2,id:"cbxmcratingreview_q_field_"+r,"onUpdate:modelValue":d=>e.log.questions[r]=d,class:"form-control mt-5",placeholder:c.placeholder},null,8,Qt)),[[N,e.log.questions[r]]]):h("",!0),c.type==="number"?m((n(),_("input",{key:3,id:"cbxmcratingreview_q_field_"+r,type:"number",class:"form-control","onUpdate:modelValue":d=>e.log.questions[r]=d,placeholder:c.placeholder},null,8,Jt)),[[N,e.log.questions[r]]]):h("",!0),c.type==="select"?m((n(),_("select",{key:4,class:"form-control mt-5","onUpdate:modelValue":d=>e.log.questions[r]=d},[(n(!0),_(f,null,R(c.options,(d,b)=>(n(),_("option",{value:b,key:b},l(d.text),9,jt))),128))],8,Ot)),[[K,e.log.questions[r]]]):h("",!0),c.type==="radio"?(n(!0),_(f,{key:5},R(c.options,(d,b)=>(n(),_("div",{class:"radio-input mt-10 magic-radio-field mt-5",key:b},[m(t("input",{type:"radio",id:"cbxmcratingreview_q_field_"+r+"_"+b,value:b,name:"cbxmcratingreview_q_field_"+r,class:"magic-radio",checked:b==e.log.questions[r],"onUpdate:modelValue":I=>e.log.questions[r]=I},null,8,Gt),[[Y,e.log.questions[r]]]),t("label",{for:"cbxmcratingreview_q_field_"+r+"_"+b},l(d.text),9,Xt)]))),128)):h("",!0),c.type==="multicheckbox"?(n(!0),_(f,{key:6},R(c.options,(d,b)=>(n(),_("div",{class:"radio-input mt-10 magic-radio-field mt-5",key:b},[m(t("input",{type:"checkbox",id:"cbxmcratingreview_q_field_"+r+"_"+b,class:"magic-checkbox","onUpdate:modelValue":I=>e.log.questions[r][b]=I,"true-value":"1","false-value":"0"},null,8,Kt),[[x,e.log.questions[r][b]]]),t("label",{for:"cbxmcratingreview_q_field_"+r+"_"+b},l(d.text),9,Yt)]))),128)):h("",!0)],8,Bt))),256))])])):h("",!0),e.cbxmcratingreviewpro_active&&e.log?(n(),_(f,{key:1},[e.cbxmcratingreview_vue_var.enable_video?(n(),_(f,{key:0},[e.log.attachment?(n(),_("div",Zt,[t("label",null,l(e.translations.add_video),1),s[9]||(s[9]=t("br",null,null,-1)),m(t("input",{type:"text","onUpdate:modelValue":s[3]||(s[3]=c=>e.log.attachment.video_url=c),class:"form-control",placeholder:e.translations.video_url_placeholder},null,8,$t),[[N,e.log.attachment.video_url]])])):h("",!0)],64)):h("",!0),e.cbxmcratingreview_vue_var.enable_photo?(n(),_(f,{key:1},[t("div",es,[t("h3",null,l(e.translations.photos)+" ("+l(e.translations.max_photos+": "+e.cbxmcratingreview_vue_var.max_number_of_files)+")",1)]),(n(),G(a,{uploadedFiles:(J=(Q=e.log)==null?void 0:Q.attachment)!=null&&J.photos?e.log.attachment.photos:[],front:!1,key:e.key,onRefreshData:i.refreshData},null,8,["uploadedFiles","onRefreshData"]))],64)):h("",!0)],64)):h("",!0)]}),_:1},8,["onSubmit"])])])],64)}const ss=A(nt,[["render",ts]]),os={name:"EditLog",components:{Log:ss},data(){return{log_id:Number(this.$route.params.id)}}};function ls(o,s,u,v,e,i){const g=w("Log");return n(),G(g,{log_id:e.log_id},null,8,["log_id"])}const is=A(os,[["render",ls]]),as=Z(),rs=$({history:as,routes:[{path:"/log/:id",component:is},{path:"/",component:rt}]});D("required",o=>!!o||"This field is required.");D("email",o=>/.+@.+\..+/.test(o)||"Must be a valid email.");D("minValue",(o,[s])=>parseFloat(o)>=parseFloat(s)||`The value must be at least ${s}.`);D("maxValue",(o,[s])=>parseFloat(o)<=parseFloat(s)||`The value must be no more than ${s}.`);X.defaults.headers.common["X-WP-Nonce"]=cbxmcratingreview_vue_var.rest_nonce;const ns=ee(le).use(re).use(ne({key:"$vfm",componentName:"VueFinalModal",dynamicContainerName:"ModalsContainer"})).use(te,X).use(se).use(rs).component("inline-svg",oe).component("QuillEditor",ce).component("Form",be).component("Field",he).component("ErrorMessage",ge);ns.mount("#cbxmcratingreview-review-public");
