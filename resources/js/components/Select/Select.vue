<template>
    <div 
        :class="[open ? 'active' : '','select__wrapper',classList]" @click="open = !open"
        v-click-outside="outsideClick"
        v-if="rendering"
    >
        <p> 
            {{selectPlaceholder}}
  
        </p>
        <div class="select__inner position-inner">
          
            <div 
                class="select__value select-category" 
                v-for="(item,key) in categories"
                :key="key"
                :data-key="key"
                @click.prevent.stop
                @mouseenter="selectIn(key)"
            >
                <span>{{item.name}}</span>
                <span 
                    v-show="item.selected"
                    class="select__check"
                >
                    <b-icon-check/>
                </span>
                <div :data-key="key" class="subcategories subcategories-list" @mouseleave="dropAll">
                    <div 
                        v-for="(sub,keygen) in subcategoriesList(item.id)"
                        :class="[sub.selected ? 'active' : '','select__value']"
                        :data-catkey="key"
                        :key="keygen"   
                        :id="`subcat-${sub.id}`"
                        :data-category="sub.parent"
                        v-if="subcategoriesRender"
                        @click.prevent.stop="moveSubcategory(sub.id)"
                    >
                        <span>{{sub.name}}</span>
                        <span 
                            v-if="sub.selected"
                            class="select__check"
                        >
                            <b-icon-check/>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <input 
            type="hidden"
            name="subcategories"
            v-model="activeSubcategories"
        >
        <input 
            type="hidden"
            name="categories"
            v-model="activeCategories"
        >
    </div>
</template>


<script>
export default {
    name: "Select",
    props:[
        "selectData",
        "selectName",
        "selectPlaceholder",
        "classList",
        "previosSelected",
    ],
    data(){
        return {
            selectedValues:[],
            valuesData: this.selectData,
            selectedCount: 0,
            rendering: true,
            open: false,
            categories:[],
            tmp_id:null,
            subcategoriesRender:true,
            subcategories:[],
            activeSubcategories:[],
            activeCategories:[],
        }
    },

    methods:{
        subcategoriesList(id){
            return this.subcategories.filter(item => item.parent == id)
        },
        outsideClick(){
            this.open = false
        },
        selectIn(key){
            this.dropAll()
            let id = this.categories[key].id
            setTimeout(()=>{
                let subcats = document.querySelector(`.subcategories-list[data-key="${key}"]`)
                subcats.classList.add("active")
                document.querySelector(`.subcategories-list:not(.subcategories-list[data-key="${key}"])`).classList.remove("active")
            },250)
            setTimeout(
                () => {
                    document.querySelectorAll(`.subcategories-list`).forEach(item => {if(item.getAttribute("data-key") != key){item.classList.remove("active")}})
                },
                500
            )
        },
        selectOut(key){
            document.querySelector(`.subcategories-list[data-key="${key}"]`).classList.remove("active")
        },
        dropAll(){
            document.querySelectorAll(`.subcategories-list`).forEach(item => {item.classList.remove("active")})

        },
        moveSubcategory(id){
            this.subcategories.map(item=>{
                if(item.id == id){
                    item.selected = !item.selected
                    let pos = null;
                    this.activeSubcategories.map((el,keygen) => {if(el == item.id) pos = keygen;})
                    if(pos == null){
                        this.activeSubcategories.push(item.id)
                    }else{
                        this.activeSubcategories.splice(pos,1)
                    }
                }
                
            })   
            if(this.activeSubcategories.length != 0){
                let categories = []
                this.categories.map(item => {item.selected = false})
                setTimeout(
                    () => {
                        document.querySelectorAll('.subcategories-list .select__value').forEach(item => {
                            if(item.classList.contains("active")){
                                categories.push(item.getAttribute("data-catkey"))
                            }
                        })
                        categories = categories.filter(
                            (value, index, self) => self.indexOf(value) === index
                        )
                        categories.map(
                            item => {
                                this.activeCategories.push(this.categories[item].id)
                                this.categories[item].selected = true
                            }
                        )


                    },
                    250
                )
            }else{
                this.activeCategories = []
                this.categories.map(item => {item.selected = false})
            }
        }
    },
    mounted(){
        this.categories = this.valuesData.filter(item => item.parent == null)
        this.subcategories = this.valuesData.filter(item => item.parent != null)
        this.categories.map(item => item.selected = false)
        this.subcategories.map(item => item.selected = false)
        
    },

}
</script>

<style>
    .select__wrapper{
        width: auto;
        height: auto;
        position: relative;
        cursor: pointer;
        overflow: hidden;
        max-width: fit-content;
        user-select: none;
    }
    .select__wrapper p{
        font-size: 14px;
        color: #b3b3b3;
        width: fit-content;
        border: 1px solid #b3b3b3;
        padding: 3px 25px;
        border-radius: 3px;
    }
    .select__inner{
        opacity: 0;
        position: absolute;
        z-index: 500;
        transition: .1s linear;
        width: fit-content;
        display: none;
        padding: 5px 0;
        border-radius: 3px;
        border: 1px solid #b3b3b3;
        min-width: 128px;
        background: white;
        transform: translateY(-100%);
    }
    .select__wrapper.active{
        overflow: unset;
    }
    .select__wrapper.active > .select__inner{
        display: block;
        opacity: 1;
        transform: translateY(0);
    }
    .select__value{
        display: flex;
        justify-content: space-between;
        padding: 0 5px;
        align-items: center;
        min-width: 120px;
    }
    .subcategories{
        min-height: 100%;
        position: absolute;
        padding: 5px 0;
        border-radius: 3px;
        top: 0;
        border: 1px solid #b3b3b3;
        background: white;
        left: 100%;
        display: none;
    }
    .subcategories.active{
        display: block;
    }
</style>