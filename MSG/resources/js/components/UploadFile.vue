<template>


  <div class="container" v-if="showForm">
    <div class="progress" style="height: 40px" v-if="showProgress">
      <div class="progress-bar" role="progressbar" :style="{ width: fileProgress + '%' }">
        {{ fileCurrent }} %
      </div>
    </div>



  <!--  <input type="file" name="image" multiple="" @change="fileInputChange">-->


    <div>
      <div v-if="filesOrder.length !== 0">
        <p class="list-center">Файлы в очереди ({{ filesOrder.length }})</p>
          <ul class="list-group">
            <li class="list-group-item" v-for="file in filesOrder">
              {{ file.name }} : {{ file.type }}
            </li>
          </ul>
      </div>
      <div>
        <p class="list-center">Загруженные файлы ({{ filesFinish.length }})</p>
          <ul class="list-group">
            <li class="list-group-item" v-for="(file, index) in filesFinish">
              {{ file.name }} : {{ file.type }}  <b-link @click="remove(index)">Удалить</b-link>
            </li>
          </ul>
      </div>
    </div>
{{this.attachcount}}
        </div>


</template>

<script>
  export default {
    data() {
        return {
          filesOrder: [],
          filesFinish: [],
          fileProgress: 0,
          fileCurrent: '',
          showForm: false,
          showProgress: false,
          attachcount: 0
        }
        },
        mounted() {
          /// из form
          this.$root.$on('fileInputChange', () => {
            this.fileInputChange()
          })

        },
        methods: {

            remove(index){
              //alert('fff');
                this.filesFinish.splice(index, 1);

                //if(index === 0) {
                //  this.attachcount = 0;
                //} else {

                for (let key in this.filesFinish) {
                  //alert('111');
                  //console.log(this.filesFinish.hasOwnProperty(key));
                  if(this.filesFinish.hasOwnProperty(key)){
                    console.log(`${key} : ${this.filesFinish[key]['idbase']}`)
                    if(key == 0) {
                      this.attachcount = this.filesFinish[key]['idbase'];
                    } else {
                      this.attachcount = this.attachcount + ',' + this.filesFinish[key]['idbase'];
                    }
                  //  if(key == 'undefined'){
                  //    this.attachcount = 0;
                  //  }
                  }
                }
              //}

              //if(index == 0) {
              //  this.$root.$emit('idBaseFile',0);
              //}else{
                this.$root.$emit('idBaseFile',this.attachcount);
              //}
                //console.log(this.filesFinish.idbase);

              //this.filesFinish[key] = '';
              //filesFinish.idbase = response.data;

              if(this.filesFinish.length === 0) {
                this.showForm = false;
              }



              //this.$emit('sendfiles',this.todos)
            },
          async fileInputChange(){
            this.showForm = true;
            this.showProgress = true;

            let files = Array.from(event.target.files);

            this.filesOrder = files.slice()

            for(let item of files) {
              await this.uploadFiles(item);

              //console.log(item)
            }
            //console.log(this.filesFinish);
          },
          async uploadFiles(item) {

            let form = new FormData()
            form.append('file', item)


            /// это для подсчета файлов
            //let countf = this.filesFinish.length = 1;


            //form.append('attach', this.filesFinish)

            await axios.post('/upload', form, {
                onUploadProgress: (itemUpload) => {
                  this.fileProgress = Math.round( (itemUpload.loaded / itemUpload.total) * 100 )
                  this.fileCurrent = item.name + ' ' + this.fileProgress
                }
            })
            .then(response => {
              //console.log(response.data);

                if(response.status == 200) {
                  this.fileProgress = 0;
                  this.fileCurrent = '';
                  item.idbase = response.data;
                  this.filesFinish.push(item);
                  //this.filesFinish.push(response.data);
                  this.filesOrder.splice(item, 1);
                  this.showProgress = false;
                  this.$parent.scrollToDown();

                  this.$root.$emit('filesFinish',this.filesFinish)

                  // добавить в инпут id файлы
                    for (var y = 0; y < this.filesFinish.length; y++){
                        console.log(this.filesFinish[y].idbase)

                        if(y == 0) {
                          this.attachcount = this.filesFinish[y].idbase;
                        } else {
                          this.attachcount = this.attachcount + ',' + this.filesFinish[y].idbase;
                        }
                    }

                    this.$root.$emit('idBaseFile',this.attachcount)
                    //this.$root.idBaseFile(this.attachcount);

                  //this.$root.AttachFile(this.filesFinish);

                  //this.$parent.attachFile();
                  //this.$parent.attachfile = 55;

                } else {
                  alert('Ошибка');
                }

            })
            .catch(error => {
              this.$bvModal.msgBoxOk('Ошибка. Файл слишком большой.')
              //alert('Ошибка. Файл слишком большой.');
              this.fileProgress = 0;
              this.fileCurrent = '';
              this.filesOrder = '';
              this.showProgress = false;
              console.log(error);
                            //alert(this.filesFinish);
                if(this.filesFinish == '' || this.filesFinish == null){
                  this.showForm = false;
                }
            })

            //console.log(this.filesFinish);
          }
        }

  }
</script>

<style scoped>

</style>
