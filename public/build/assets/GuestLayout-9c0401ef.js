import{o as t,f as d,b as i,r as m,c as s,w as a,d as o,u as n,g as l,h as c}from"./app-53947144.js";const u={class:"min-h-screen flex flex-col sm:justify-center items-center sm:pt-0 bg-gray-100"},g={class:"w-full sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"},x={__name:"GuestLayout",props:{canLogin:Boolean,canRegister:Boolean},setup(r){return(e,f)=>(t(),d("div",u,[i("div",g,[m(e.$slots,"default")]),r.canRegister?(t(),s(n(l),{key:0,href:e.route("register"),class:"ml-4 mt-4 text-sm text-gray-700 dark:text-gray-500 underline"},{default:a(()=>[o("Register")]),_:1},8,["href"])):c("",!0),r.canLogin==!0?(t(),s(n(l),{key:1,href:e.route("register"),class:"ml-4 text-sm text-gray-700 dark:text-gray-500 underline"},{default:a(()=>[o("Login")]),_:1},8,["href"])):c("",!0)]))}};export{x as _};
