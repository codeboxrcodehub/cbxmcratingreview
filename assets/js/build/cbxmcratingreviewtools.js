import{_ as f,r as x,o as l,f as r,a as t,t as n,b as k,F as _,x as u,n as v,e as y,j as C,g as h,A,B as M,C as p,D as V,E as B,H as E,I as H,G as N}from"./vue-router.js";import{d as g}from"./vee-validate.js";const D={components:{},data(){return{cbxmcratingreview_vue_var,option_array:cbxmcratingreview_vue_var.option_array,reset_options:{},show_option_table:!1,show_migration_data:!1,translations:cbxmcratingreview_vue_var.translations}},methods:{checkAll(){this.option_array.forEach(i=>{this.reset_options[i.option_name]=!0})},uncheckAll(){this.option_array.forEach(i=>{this.reset_options[i.option_name]=!1})},async reset(){if(!Object.values(this.reset_options).some(c=>c)){this.errorToast(cbxmcratingreview_vue_var.translations.tools.please_select_one);return}let i=cbxmcratingreview_vue_var.rest_end_points.reset_option,o={reset_options:this.reset_options};await this.axios.post(i,o).then(c=>{c.data.success?this.successMessage(c.data.info):c.data.info&&this.errorMessage(c.data.info)})},async migrate(){let i=cbxmcratingreview_vue_var.rest_end_points.migrate_table;await this.axios.post(i,{}).then(o=>{o.data.success?(this.successMessage(o.data.info),window.location.reload(!0)):o.data.info&&this.errorMessage(o.data.info)})}}},T={class:"container"},j={class:"row"},q={class:"col-12 mb-20"},F={class:"wp-heading-wrap"},I={class:"wp-heading-wrap-left pull-left"},R={class:"wp-heading-inline wp-heading-inline-cbxmcratingreview"},S={class:"wp-heading-wrap-right pull-right"},U={class:"button_actions button_actions-global-menu"},W={class:"dropdown dropdown-menu ml-10"},z={class:"button outline primary icon icon-only"},G={class:"cbx-icon"},L={class:"card card-menu card-menu-right"},O={id:"dashboard_menus"},P=["href","title"],X={class:"container"},J={class:"row"},K={class:"col-12"},Q={class:"postbox"},Y={class:"inside setting-form-wrap"},Z={class:"reset-section"},$={class:"row"},tt={class:"col"},st={class:"col"},ot={key:0,id:"tools-section-content tools-section-content-reset"},et={class:"row"},nt={class:"col-12"},it={class:"row"},lt={class:"col-6"},rt={style:{"margin-bottom":"10px"},class:"grouped gapless grouped_buttons",id:"cbxmcratingreview_setting_options_check_actions"},at={class:"col-6"},ct={class:"widefat widethin cbxmcratingreview_table_data",id:"cbxmcratingreview_setting_options_table"},_t={class:"row-title"},dt={class:"row-title"},ut=["onUpdate:modelValue","id","value"],ht=["for"],mt={class:"row-title"},vt={class:"tools-section tools-section-migration"},pt={class:"row tools-section-header tools-section-header-migration"},gt={class:"col mb-0"},bt={class:"col mb-0"},wt={key:0,class:"tools-section-content tools-section-content-migration"},ft={class:"table table-bordered table-hover table-striped"},xt={class:"row"},kt={class:"col mb-0"};function yt(i,o,c,Vt,s,a){const b=x("inline-svg");return l(),r(_,null,[t("div",T,[t("div",j,[t("div",q,[t("div",F,[t("div",I,[t("h1",R,n(s.translations.tools.heading),1)]),t("div",S,[t("div",U,[t("details",W,[t("summary",z,[t("i",G,[k(b,{src:s.cbxmcratingreview_vue_var.icons_url+"icon_more_v.svg"},null,8,["src"])]),o[6]||(o[6]=t("span",{class:"sr-only"},null,-1))]),t("div",L,[t("ul",O,[(l(!0),r(_,null,u(s.cbxmcratingreview_vue_var.dashboard_menus,e=>(l(),r("li",null,[t("a",{href:e.url,class:"button outline dashboard_menu dashboard_menu_",role:"button",title:e["title-attr"]},n(e.title),9,P)]))),256))])])])])])])])])]),t("div",X,[t("div",J,[t("div",K,[t("div",Q,[t("div",Y,[t("div",Z,[t("div",$,[t("div",tt,[t("h2",null,n(s.translations.tools.reset_option_data),1)]),t("div",st,[t("button",{class:"button secondary ml-20",onClick:o[0]||(o[0]=e=>s.show_option_table=!s.show_option_table)},n(s.translations.tools.show_hide),1)])]),s.show_option_table?(l(),r("div",ot,[t("div",et,[t("div",nt,[t("strong",null,n(s.translations.tools.following_option_values),1)])]),t("div",it,[t("div",lt,[t("p",rt,[t("button",{class:"button primary cbxmcratingreview_setting_options_check_action_call",onClick:o[1]||(o[1]=(...e)=>a.checkAll&&a.checkAll(...e))},n(s.translations.tools.check_all),1),t("button",{class:"button outline cbxmcratingreview_setting_options_check_action_ucall",onClick:o[2]||(o[2]=(...e)=>a.uncheckAll&&a.uncheckAll(...e))},n(s.translations.tools.uncheck_all),1)])]),t("div",at,[t("button",{class:"button secondary ml-20 pull-right",onClick:o[3]||(o[3]=(...e)=>a.reset&&a.reset(...e))},n(s.translations.tools.reset_data),1)])]),t("table",ct,[t("thead",null,[t("tr",null,[t("th",_t,n(s.translations.tools.option_name),1),t("th",null,n(s.translations.tools.option_id),1)])]),t("tbody",null,[(l(!0),r(_,null,u(s.option_array,(e,m)=>(l(),r("tr",{class:v(m%2==0?"alternate":"")},[t("td",dt,[y(t("input",{class:"magic-checkbox reset_options",type:"checkbox","onUpdate:modelValue":w=>s.reset_options[e.option_name]=w,id:"reset_options_"+e.option_id,value:e.option_name},null,8,ut),[[C,s.reset_options[e.option_name]]]),t("label",{for:"reset_options_"+e.option_id},n(e.option_name),9,ht)]),t("td",null,n(e.option_id),1)],2))),256))]),t("tfoot",null,[t("tr",null,[t("th",mt,n(s.translations.tools.option_name),1),t("th",null,n(s.translations.tools.option_id),1)])])])])):h("",!0)]),o[8]||(o[8]=t("hr",null,null,-1)),t("div",vt,[t("div",pt,[t("div",gt,[t("h2",null,n(s.translations.tools.migration_files),1)]),t("div",bt,[t("button",{class:"button secondary ml-20",onClick:o[4]||(o[4]=e=>s.show_migration_data=!s.show_migration_data)},n(s.translations.tools.show_hide),1)])]),s.show_migration_data?(l(),r("div",wt,[t("table",ft,[t("thead",null,[t("tr",null,[t("th",null,n(s.translations.tools.migration_file_name),1),t("th",null,n(s.translations.tools.status),1)])]),t("tbody",null,[(l(!0),r(_,null,u(s.cbxmcratingreview_vue_var.migration_files,(e,m)=>(l(),r("tr",null,[t("td",null,n(e),1),t("td",null,[t("span",{class:v(s.cbxmcratingreview_vue_var.migration_files_left.includes(e)?"text-error":"text-success")},n(s.cbxmcratingreview_vue_var.migration_files_left.includes(e)?s.translations.tools.need_migrate:s.translations.tools.done),3)])]))),256))]),t("tfoot",null,[t("tr",null,[t("th",null,n(s.translations.tools.migration_file_name),1),t("th",null,n(s.translations.tools.status),1)])])]),t("div",xt,[o[7]||(o[7]=t("div",{class:"col mb-0"},null,-1)),t("div",kt,[s.cbxmcratingreview_vue_var.migration_files_left.length?(l(),r("button",{key:0,class:"button success",onClick:o[5]||(o[5]=(...e)=>a.migrate&&a.migrate(...e))},n(s.translations.tools.run_migration),1)):h("",!0)])])])):h("",!0)])])])])])])],64)}const Ct=f(D,[["render",yt]]),At=A(),Mt=M({history:At,routes:[{path:"/",component:Ct}]});g("required",i=>!!i||"This field is required.");g("email",i=>/.+@.+\..+/.test(i)||"Must be a valid email.");p.defaults.headers.common["X-WP-Nonce"]=cbxmcratingreview_vue_var.rest_nonce;const d=V(N);d.use(B,p);d.use(E);d.component("inline-svg",H);d.use(Mt).mount("#cbxmcratingreview-tools");
