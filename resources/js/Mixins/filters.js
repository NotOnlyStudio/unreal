export const filters = {
    methods:{
        // Input filtrations
        inpFilter(e){
            // console.log({inpFilter:e})
            var char = /["?!,.a-zA-Z0-9\s]/;
            var val = String.fromCharCode(e.which);
            var test = char.test(val);
            console.log(123);
            if(!test){
                // this.cardTitle = this.cardTitle.slice(0,-1)
                alert("Only latin characters!")
                e.preventDefault()
                return false
            }
        },
        inpFilterTags(e){
            var char = /[^@#$%^&*()_]/;
            var val = String.fromCharCode(e.which);
            var test = char.test(val);
            if(!test){
                alert("Symbols are banned!")
                e.preventDefault()
                return false
            }
        }
    }
}
