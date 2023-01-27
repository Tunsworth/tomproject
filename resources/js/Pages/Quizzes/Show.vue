<template>
<Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="tabs-component flex">
                        <ul class="tabs-component-tabs">
                            <li @click.prevent="changeTab" class="tabs-component-tab" :class="pageTab == 'information' ? 'is-active' : 'not-active' " >
                                <a class="class-tabs-component-tab-a" >
                                    Information
                                </a>
                            </li>
                            <li  @click.prevent="changeTab" class="tabs-component-tab" :class="pageTab == 'questions' ? 'is-active' : 'not-active' ">
                                <a class="class-tabs-component-tab-a">
                                    Questions
                                </a>
                            </li>
                        </ul>
                    </div>   
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div v-if="pageTab == 'information'" class="flex content-start justify-between">
                            <div>
                                <h1>Title: {{ quiz.title }}</h1>
                                <h2>Description: {{ quiz.description }}</h2>
                                <h2>Questions: {{ quiz.questions.length  }}</h2>
                            </div>
                            <div class="flexflex-row space-between">
                                <h2>Category: {{ quiz.category.title}}</h2>
                                <div v-if="$page.props.auth.roles.includes('edit')">
                                    <Link
                                        tabIndex="1"
                                        className="px-4 py-2 text-sm text-white bg-blue-500 rounded"
                                        :href="route('quizzes.edit', quiz.id)"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="destroy(quiz.id)"
                                        tabIndex="-1"
                                        type="button"
                                        className="mx-1 px-4 py-2 text-sm text-white bg-red-500 rounded"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-else-if="pageTab != 'information'">
                          <div v-for="(question , index) in quiz.questions" :key="index">
                              <div>
                                  <div className="mb-4">
                                      <Label for="question" :value="`Question ${index+1}`" />
                                      <h1>{{question.question}}</h1>
                                      <div v-if="!$page.props.auth.roles.includes('restricted')" >
                                        <div class="grid justify-items-end">
                                            <button class="px-2 text-white bg-green-500 rounded-md focus:outline-none text-sm" @click.prevent="showAnswer(index)">
                                                <span v-if="!revealAnswer.includes(index)" >Reveal Answers</span>
                                                <span v-else >Hide Answers</span>
                                            </button> 
                                        </div>
                                            <div v-if="revealAnswer.includes(index)" class="answers p-4 mt-4" > 
                                                <div class="" v-for="(answer, number) in question.answers">   
                                                    <div class="flex py-2">
                                                        <div :class="answer.correct_answer == true ? 'marked-as-correct' : '' ">{{ getLetter(number) }}. {{ answer.answer }}</div>
                                                    </div>
                                                </div>   
                                            </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { computed, ref, reactive } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Label from '@/Components/InputLabel.vue';
import Input from '@/Components/TextInput.vue';
import TextArea from '@/Components/Textarea.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    quiz: Object,
});

const pageTab = ref('information');
const revealAnswer = reactive([])


const  changeTab = () => {
    if(pageTab.value == 'information'){
      pageTab.value = 'questions'
    }else {
      pageTab.value = 'information'
    }   
};

const getLetter= (index) => {
    let letters = ['A','B','C','D','E']
    let letter = letters[index]
    return letter
}

const showAnswer = (index) => {
    if(revealAnswer.includes(index)){
        const idx = revealAnswer.findIndex(item => {
            return item === index;
        });
        revealAnswer.splice(idx,1) 
    }else {
        revealAnswer.push(index)  
    }
}

const destroy = (id) => {
    if(confirm('Are you sure?')){
        Inertia.delete(route('quizzes.destroy', id));
    }
    return {destroy}
}


</script>

<style lang="scss" scoped>
.answers{
    column-count: 2;  
    border-radius:5px 5px 5px 5px ;
    border: 1px solid #efefef;
    // background: #F8FAFC !important;
    box-shadow:inset 1px 1px 4px #efefef;
    box-shadow: 0px 0px 3px #0E67B4;
    outline: none;
}
.marked-as-correct {
    color: #19c52b;
    font-weight: 900;
    // text-shadow:1px 1px 1px #6fce79;

}
    h1{
        font-size: 1.2rem;
        font-weight: 900;
        color: #0E67B4;
    }
    h2{
        font-size: 1rem;
        font-weight: 700;
        // color: #0E67B4;
    }
    .tabs-component-tabs {
        width: 100vw;
        display: flex;
        justify-content:space-between;
        white-space: nowrap;
    }

    .tabs-component-tab {
        height: 3rem;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #A3AFC0;
        font-size: 14px;
        font-weight: 600;
        list-style: none;
        position:relative;
    }

    .tabs-component-tab:hover {
        color: #666;
        text-shadow: 0px 0px 10px rgb(111, 71, 255,0.2);
        z-index: 2;
        -webkit-box-shadow: inset 0px 0px 2px #efefef;
           -moz-box-shadow: inset 0px 0px 2px #efefef;
                box-shadow: inset 0px 0px 2px #efefef;
        outline: none;
        cursor: pointer;
    }
    .tabs-component-tab.is-active {
        // border:2px solid rgba(166, 165, 166, 0.4); 
        border-bottom: none;
        border-radius:5px 5px 0px 0px ;
        color: black;
        text-shadow: 0px 0px 10px rgb(111, 71, 255,0.2);
        z-index: 2;
        transform: translateY(1px);
        outline: none;
    }
    .tabs-component-tab.not-active {
         background: #F8FAFC !important;
         -webkit-box-shadow: inset 0px 0px 5px #efefef;
           -moz-box-shadow: inset 0px 0px 5px #efefef;
                box-shadow: inset 0px 0px 5px #efefef;
        outline: none;
    }

</style>