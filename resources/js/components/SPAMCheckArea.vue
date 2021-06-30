<template>
   <form method="post" @submit.prevent="checkMessage">
       <template v-if="this.result && this.result.is_spam === false">
        <div class="alert success">
            <div class="flex-item-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $messages.hamMessage }}
            </div>
            <div class="report">{{ $messages.reportMessage }} <a href="#" @click.prevent="reportResults">{{ $messages.reportLinkMessage }}</a>
            </div>
        </div>
      </template>
      <template v-if=" this.result.is_spam === true">
          <div class="alert secondary">
            <div class="flex-item-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $messages.spamMessage }}
            </div>
            <div class="report">{{ $messages.reportMessage }} <a href="#" @click.prevent="reportResults">{{ $messages.reportLinkMessage }}</a>
            </div>
        </div>
      </template>
      <div class="alert success" v-if="report_sent === true">
         <div class="flex-item-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $messages.reportSentMessage }}
         </div>
      </div>
      <div class="alert secondary" v-if="server_error === true">
         <div class="flex-item-center">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $messages.serverErrorMessage }}
         </div>
      </div>
      <textarea rows="3"
         class="main-section-field"
         :placeholder="$messages.textAreaMessage"
         v-model="form.message"></textarea>
      <div class="main-section-btns">
         <template v-if="loading">
             <button type="button"
            class="btn-disabled" :disabled="true">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 animate-spin" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            {{ $messages.loadingButtonMessage }}
         </button>
         </template>
        <template v-else-if="form.message.length > 0">
             <button type="submit"
            class="btn" @click.prevent="checkMessage">{{ $messages.checkButtonMessage }}</button>
        </template>
      </div>
   </form>
</template>
<script>
    import axios from "axios";
   export default {
       props: {
           storeMessageUrl: {
               type: String,
               required: true,
           },
            predictMessageUrl: {
               type: String,
               required: true,
           },
           reportUrl: {
               type: String,
               required: false,
               default: "",
           },
       },

       data() {
           return {
               loading: false,
               form: {
                   message: "",
               },
               message: {},
               result: {},
               server_error: false,
               report_sent: false,
           }
       },

       methods: {
           async storeMessage() {
               try {
                   let response = await axios.post(this.storeMessageUrl, this.form);
                   this.message = response.data.data;
                   this.server_error = false;
               } catch(e) {
                   this.server_error = true;
               }
           },

           async checkMessage() {
                try {
                    this.message = {};
                    this.result = {};
                    this.loading = true;
                    this.report_sent = false;
                    
                    // Creating the message
                    await this.storeMessage();

                    if (this.server_error === true) {
                        return;
                    }

                    let response = await axios.post(this.predictMessageUrl.replace("#id", this.message.id));
                    this.result = response.data.result;
               } catch(e) {
                   this.server_error = true;
               } finally {
                    this.loading = false;
                    this.form.message = "";
               }
           },
           async reportResults() {
               try {
                   this.result = {};
                   
                   if (! this.message.id) {
                       return;
                   }
                   let response = await axios.post(this.reportUrl.replace("#id", this.message.id), {
                       'correct_value': !this.result.is_spam,
                   });
               } catch(e) {
                   
               } finally {
                   this.report_sent = true;
               }
           }
       }
   }
</script>
<style>
</style>