import{P as n,c as l,w as t,o as d,a,u as o,X as c,b as e,d as f,n as p,e as u}from"./app-53947144.js";import{_}from"./GuestLayout-9c0401ef.js";import{_ as w}from"./InputError-bbcc523b.js";import{_ as b}from"./InputLabel-071afcaf.js";import{_ as h}from"./PrimaryButton-eb3f815b.js";import{_ as x}from"./TextInput-a49c9a9a.js";const g=e("div",{class:"mb-4 text-sm text-gray-600"}," This is a secure area of the application. Please confirm your password before continuing. ",-1),V=["onSubmit"],v={class:"flex justify-end mt-4"},T={__name:"ConfirmPassword",setup(y){const s=n({password:""}),i=()=>{s.post(route("password.confirm"),{onFinish:()=>s.reset()})};return(C,r)=>(d(),l(_,null,{default:t(()=>[a(o(c),{title:"Confirm Password"}),g,e("form",{onSubmit:u(i,["prevent"])},[e("div",null,[a(b,{for:"password",value:"Password"}),a(x,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:o(s).password,"onUpdate:modelValue":r[0]||(r[0]=m=>o(s).password=m),required:"",autocomplete:"current-password",autofocus:""},null,8,["modelValue"]),a(w,{class:"mt-2",message:o(s).errors.password},null,8,["message"])]),e("div",v,[a(h,{class:p(["ml-4",{"opacity-25":o(s).processing}]),disabled:o(s).processing},{default:t(()=>[f(" Confirm ")]),_:1},8,["class","disabled"])])],40,V)]),_:1}))}};export{T as default};
