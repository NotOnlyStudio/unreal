<template>
    <spinner v-if="!loaded"/>
    <div v-else>
        <div>
          <div class="mb-3">
            <a type="button" class="btn btn-danger" :href="route+'?status=1'">Show Deleted</a>
            <a type="button" class="btn btn-primary" :href="route+'?status=0'">Show Undeleted</a>
            <div class="input-group mt-2" style="width: 400px">
              <input type="text" class="form-control" v-model="searchs" placeholder="Words search" aria-label="Words search" aria-describedby="button-addon2">
            </div>
          </div>
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <th>ID</th>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Mark as read</th>
                    <th v-if="this.status == 0">Delete</th>
                    <th v-if="this.status == 1">Recover</th>
                </thead>
                <tbody>
                    <tr v-for="(report,key) in reports.filter(item => item.title.toLowerCase().indexOf(this.searchs) !== -1 )" :key="key">
                        <td>{{report.id}}</td>
                        <td>{{report.title}}</td>
                        <td><b-button variant="primary" @click="seeDescription(key)"><b-icon-file-earmark-medical/></b-button></td>
                        <td>
                            <b-button
                                variant="warning"
                                @click="markAsRead(key)"
                                :disabled="report.view ? true : false"
                                :title="report.view ? 'Message was readed' : 'Message unread'"
                            >
                                <b-icon
                                    :icon="report.view ? 'eye-fill' : 'eye'"
                                />
                            </b-button>
                        </td>
                        <td v-if="status == 0"><b-button variant="danger" @click="deleteReport(key)"><b-icon-x/></b-button></td>
                        <td v-if="status == 1"><b-button variant="success" @click="recoverReport(key)"><b-icon-x/></b-button></td>
                    </tr>
                </tbody>
            </table>
            <b-modal @hide="answer = ''" @cancel="answer=''" @ok="sendAnswer" hidefooter ref="modal-descr" :title="'Subject: '+title" ok-title="Send message">
                <p><b>Message:</b><br><span v-html="text"/></p>
                <p><b>Email:</b><br>{{email}}</p>
                <b-form>
                    <b-form-group
                        label="Answer"
                        label-for="answer"
                    >
                        <b-form-textarea id="answer" v-model="answer"/>
                    </b-form-group>
                </b-form>
            </b-modal>
            <pagination  :limit="4" :data="laravelData" @pagination-change-page="getResults">
                <span slot="prev-nav">&lt; Previous</span>
                <span slot="next-nav">Next &gt;</span>
            </pagination>
        </div>
    </div>
</template>


<script>
import axios from "axios"
import spinner from "../components/Spinner";
import pagination from 'laravel-vue-pagination';
export default {

    name:"Reports",
    data(){
        return{
            loaded: false,
            laravelData: null,
            reports:[],
            text:"",
            title:"",
            answer:"",
            email:"",
            activeKey:null,
            status:0,
            searchs:"",
            route:""
        }
    },
    methods:{
        sendAnswer(){
            let item = this.reports[this.activeKey]
            let email = item.email
            let answer = this.answer
            let id = item.id
            let text = item.description
            answer = answer.replace('\n','<br>')
            answer = answer.replace('\t','<br>')
            answer = answer.replace(/\r?\n/g, '<br />')
            axios.post("/send-message",{
                "email":email,
                "answer": answer,
                "text":text,
                "id":id,
            })
            .then(
                resp => {
                    this.makeToast("Message was send")
                }
            )
        },
        markAsRead(key){
            let item = this.reports[key]
            if(!item.view){
                axios.post(`/reports/read/${item.id}`)
                .then(
                    resp => {
                        this.loaded = false
                        item.view = true
                        this.reports[key] = item
                        this.$nextTick(()=>{this.loaded = true})
                        this.makeToast("The report has been read")
                    }
                )
            }
        },
        getResults(page = 1){
          this.status = (new URLSearchParams(window.location.search)).get("status");

          if (this.status == null) this.status = 0;
          axios.get("/reports?page="+page+"&status="+this.status)
            .then(
                resp => {
                    this.loaded = false
                    this.reports = resp.data.reports.data
                    this.laravelData = resp.data.reports
                    this.loaded = true
                }
            )
        },
        seeDescription(key){
            this.activeKey = key
            let info = this.reports[key]
            this.text = info.description
            this.email = info.email
            this.title = info.title
            this.$refs["modal-descr"].show()
        },
        deleteReport(key){
            let id = this.reports[key].id
            axios.delete("/report/"+id)
            .then(
                resp => {
                    this.loaded = false
                    this.reports.splice(key,1)
                    this.$nextTick(()=>{
                        this.loaded = true
                    })
                    let count_el = document.querySelector('.reports-badge')
                    let count = parseInt(count_el.innerText)
                    count = count-1 > 0 ? count-1 : 0
                    count_el.innerText = count
                }
            )
        },
        recoverReport(key){
            let id = this.reports[key].id
            axios.patch("/recover/"+id)
            .then(
                resp => {
                  this.loaded = false
                  this.reports.splice(key,1)
                  this.$nextTick(()=>{
                    this.loaded = true
                  })
                  let count_el = document.querySelector('.reports-badge')
                  let count = parseInt(count_el.innerText)
                  count = count-1 > 0 ? count-1 : 0
                  count_el.innerText = count
                }
            )
        },
        makeToast(message,append = false) {
            this.$bvToast.toast(message, {
                title: 'UnrealShop notification',
                autoHideDelay: 5000,
                appendToast: append,
                variant: "success"
            })
        }
    },
    components:{
        spinner,
        pagination
    },
    mounted(){
        this.getResults();
        console.log(this.report);
    }
}
</script>

<style scoped>
    .btn.disabled{
        opacity: .3;
    }
</style>
