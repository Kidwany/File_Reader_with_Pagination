<template>
    <div class="p-6">
        <div class="flex items-center">
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <div class="ml-4 text-lg leading-7 font-semibold"><a href="#" class="underline text-gray-900 dark:text-white">File Reader</a></div>
        </div>

        <div class="ml-12" style="min-height: 80vh">
            <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="align-items-center">
                            <div class="col-auto">
                                <input v-model="fields.file_path" placeholder="Enter Log File Directory: Ex: htdocs/error.log" type="text" id="file_path" class="form-control" aria-describedby="passwordHelpInline">
                            </div>
                            <div class="col-auto">
                                <span id="passwordHelpInline" class="form-text">
                                  The File will be under the <strong>{{ logDirectory }}</strong> Directory Which You configured before
                                </span>
                            </div>
                            <small class="text-danger" v-if="errors && errors.file_path"> {{ errors.file_path[0] }} </small>
                            <small class="text-danger" v-if="isValidFile === false"> File you requested is not found </small>
                        </div>
                    </div>
                    <div class="col-lg-4 d-flex justify-content-between">
                        <div>
                            <button type="button" id="checkDirectory" class="btn btn-dark" @click=" getLinesToShow() " :disabled="searching">
                                Read File
                                <div class="spinner-border text-light" role="status" style="width: 20px; height: 20px" v-if="searching">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </button>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-dark dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                {{pagination.total > 0 ? pagination.per_page : ''}} Lines Per page
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" @click="setLimitPerPage(10)" href="#">10</a></li>
                                <li><a class="dropdown-item" @click="setLimitPerPage(25)" href="#">25</a></li>
                                <li><a class="dropdown-item" @click="setLimitPerPage(50)" href="#">50</a></li>
                                <li><a class="dropdown-item" @click="setLimitPerPage(100)" href="#">100</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <ul class="list-group" v-for="lineToShow in linesToShow">
                            <li class="list-group-item disabled">{{ lineToShow }}</li>
                        </ul>
                    </div>
                </div>

                <div class="row mt-3" v-if="pagination.total > 0">
                    <div class="col-2">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" @click="setPageNumber(pagination.first_page) " aria-label="Previous">
                                        <span aria-hidden="true">
                                            <img :src="'/img/first.png'">
                                        </span>
                                    </a>
                                </li>
                                <li class="page-item"><a class="page-link" @click="setPageNumber(pagination.previous_page)" ><img :src="'/img/previous.png'"></a></li>
                                <li class="page-item"><a class="page-link" @click="setPageNumber(pagination.next_page)" ><img :src="'/img/next.png'"></a></li>
                                <li class="page-item">
                                    <a class="page-link" @click="setPageNumber(pagination.last_page)" aria-label="Next">
                                        <span aria-hidden="true">
                                            <img :src="'/img/latest.png'">
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-6 sm:text-left">
                        Page number {{ pagination.current_page }} of {{ pagination.pages_count }} Pages
                    </div>
                    <div class="col-4 sm:text-right">
                        From {{ pagination.fromIndex }} to {{ pagination.toIndex }} of {{ pagination.total }} Entries
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                logDirectory : "",
                linesToShow : [],
                fields : {
                    file_path : ''
                },
                errors : {
                },
                isValidFile : '',
                pagination : {
                    total           : 0,
                    pages_count     : 0,
                    per_page        : 10,
                    first_page      : 1,
                    previous_page   : 0,
                    current_page    : 0,
                    next_page       : 0,
                    last_page       : 1,
                    fromIndex       : 0,
                    toIndex         : 0,
                },
                baseUrl : "/file-reader",
                queryParams : "",
                searching : false
            }
        },
        mounted() {
            this.getLogDirectory();
            console.log(this.userInfo)
        },
        computed : {
            axiosParams() {
                const params = new URLSearchParams();
                params.append('page', this.pagination.current_page);
                params.append('limit', this.pagination.per_page);
                return params;
            }
        },
        methods : {
            getLogDirectory: function () {
                axios.get("/get-log-directory").then(response => {
                    this.logDirectory = response.data
                })
            },
            getLinesToShow: function () {
                axios.post(this.baseUrl + this.queryParams, this.fields).then(response => {
                    this.searching                  = true
                    this.linesToShow                = response.data.lines;
                    this.pagination.total           = response.data.total;
                    this.pagination.fromIndex       = response.data.from;
                    this.pagination.toIndex         = response.data.to;
                    this.pagination.pages_count     = response.data.pages_count;
                    this.pagination.per_page        = response.data.per_page;
                    this.pagination.first_page      = response.data.first_page;
                    this.pagination.previous_page   = response.data.previous_page;
                    this.pagination.current_page    = response.data.current_page;
                    this.pagination.next_page       = response.data.next_page;
                    this.pagination.last_page       = response.data.last_page;
                    this.searching = false
                    this.errors = {};
                    this.queryParams = ""
                }).catch(error => {
                    this.searching = true
                    this.errors = {};
                    this.isValidFile = ''
                    if (error.response.status === 422)
                    {
                        this.errors = error.response.data.errors
                    }
                    else if (error.response.status === 402)
                    {
                        this.isValidFile = false
                    }
                    this.searching = false
                })
            },
            setPageNumber: function (page_number){
                this.queryParams = "?page=" + page_number + "&limit=" + this.pagination.per_page;
                this.getLinesToShow();
            },
            setLimitPerPage: function (limitPerPage)
            {
                let current_page = this.pagination.current_page
                this.queryParams = "?page=" + (current_page <= 0 ? 1 : current_page) + "&limit=" + limitPerPage;
                this.getLinesToShow();
            },
        }
    }
</script>
