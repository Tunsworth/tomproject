import{_ as m}from"./AuthenticatedLayout-5e15c7dd.js";import{Q as c}from"./QuizForm-aae4f376.js";import{j as s,P as f,f as p,a as t,u as i,w as a,F as g,o as h,X as z,b as l,g as _,d as q,G as w}from"./app-53947144.js";import"./ApplicationLogo-439b48b4.js";import"./InputLabel-071afcaf.js";import"./TextInput-a49c9a9a.js";import"./Checkbox-d1ddf446.js";const x={class:"flex justify-between"},y=l("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Edit quiz ",-1),O={__name:"Edit",props:{quiz:Object,categories:Array},setup(r){const e=r;s(!1),s(0),s("information");const u=f({id:e.quiz.id,title:e.quiz.title,description:e.quiz.description,category_id:e.quiz.category.id,questions:e.quiz.questions});return(o,n)=>{const d=w("CategoryModal");return h(),p(g,null,[t(i(z),{title:"Dashboard"}),t(m,null,{header:a(()=>[l("div",x,[y,t(i(_),{className:"px-6 py-2 text-white bg-blue-500 rounded-md focus:outline-none",href:o.route("quizzes.index")},{default:a(()=>[q("Quizzes")]),_:1},8,["href"])])]),default:a(()=>[t(d,{show:o.showCatOptions,onClose:n[0]||(n[0]=b=>o.showCatOptions=!1)},null,8,["show"]),t(c,{form:i(u),categories:r.categories,edit:!0},null,8,["form","categories"])]),_:1})],64)}}};export{O as default};