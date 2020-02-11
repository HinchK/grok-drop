<div class="container is-fluid box">
    <div class="new-file">
        <form id="new-file-form" action="#" method="#" @submit.prevent="submitForm">
            <div class="field is-grouped">
                <p class="control is-expanded">
                    <input class="input" type="text" name="name" placeholder="Data name" v-model="fileName" required>
                </p>
                <p class="control is-expanded">
                    <input class="input" type="text" name="note" placeholder=Note" v-model="fileNote" required>
                </p>
                <p class="control is-expanded">
                    <input class="input" type="text" name="site" placeholder="Site name" v-model="fileSitee" required>
                </p>
                <div class="file is-info has-name">
                    <label class="file-label">
                        <input class="file-input" type="file" ref="file" name="file" @change="addData()">
                        <span class="file-cta">
                            <span class="file-icon">
                                <i class="fa fa-upload"></i>
                            </span>
                            <span class="file-label">
                                Upload new file
                            </span>
                        </span>
                        <span class="file-name" v-if="attachment.name" v-html="attachment.name"></span>
                    </label>
                </div>
                <p class="control">
                    <button type="submit" class="button is-primary">
                        Add new file
                    </button>
                </p>
            </div>
        </form>
    </div>
</div>
