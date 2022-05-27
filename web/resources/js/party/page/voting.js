import { AbstractPage } from './abstract.js';
import { api } from '../api.js';
import { baseUrl } from "../../config.js";

class VotingPage extends AbstractPage {

    constructor() {
        super();
    }

    process(data) {
        this.contentDiv.innerHTML = '';

        const container = document.createElement('div');
        container.className = 'voting-page';

        const form = document.createElement('form');
        form.className = 'vote-form';
        form.method = 'post';
        form.action = baseUrl+'/party/vote.php';

        const title = document.createElement('p');
        title.className = 'title';
        title.innerHTML = 'C\'est l\'heure de voter les rôles !';
        form.appendChild(title);

        let i = 1;
        data.userList.forEach(user => {
            const selectLabel = document.createElement('label');
            selectLabel.setAttribute('for', 'vote-' + i);
            selectLabel.className = 'input-select-label'
            selectLabel.innerHTML = 'Le rôle de <span class="highlight">' + user.nickname + '</span> :';
            const select = document.createElement('select');
            select.id = 'vote-' + i;
            select.name = 'vote-' + i;
            select.className = 'input-select'
            select.required = true;

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.appendChild(document.createTextNode('Choisir'));
            select.appendChild(defaultOption);

            for (const role in data.roleList) {
                for (let i = 0; i < data.roleList[role]; i++) {
                    const option = document.createElement('option');
                    option.value = '{ "' + user.gs_id + '" : "' + role + '" }';
                    option.appendChild(document.createTextNode(role));
                    select.appendChild(option);
                }
            }

            form.appendChild(selectLabel);
            form.appendChild(select);

            i++;
        });

        const submit = document.createElement('input');
        submit.className = 'button';
        submit.type = 'submit';
        submit.value = 'C\'est bon !';
        form.appendChild(submit);

        container.appendChild(form);

        this.contentDiv.appendChild(container);
    }
}

export const votingPage = new VotingPage();