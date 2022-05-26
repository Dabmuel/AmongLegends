import { AbstractPage } from './abstract.js';
import { api } from '../api.js';
import { baseUrl } from "../../config.js";

let gameTypeData = null;

class LobbyPage extends AbstractPage {

    constructor() {
        super();
    }

    process(data) {
        this.contentDiv.innerHTML = '';

        const container = document.createElement('div');
        container.className = 'lobby-page';
        container.id = 'lobby-page';

        const lobbyContainer = document.createElement('div');
        lobbyContainer.className = 'lobby-container'

        const header = document.createElement('p');
        header.className = 'title';
        header.appendChild(document.createTextNode('Liste des utilisateurs:'));

        lobbyContainer.appendChild(header);

        const userContainer = document.createElement('div');
        userContainer.className = 'user-list';
        userContainer.id = 'user-list';

        lobbyContainer.appendChild(userContainer);

        container.appendChild(lobbyContainer);

        if (this.session.admin === 'true') {
            const gameContainer = document.createElement('form');
            gameContainer.id = 'admin-game-container';
            gameContainer.method = 'post';
            gameContainer.className = 'game';

            container.appendChild(gameContainer);
        }

        this.contentDiv.appendChild(container);

        this.userList(data);
    }

    processWithGametypes(data) {
        this.process(data);

        gameTypeData = data.gameTypes;

        const gameContainer = document.getElementById('admin-game-container');
        console.log(gameContainer);
        if (gameContainer != null) {
            gameContainer.innerHTML = '';

            const header = document.createElement('p');
            header.className = 'title';
            header.appendChild(document.createTextNode('Configure la partie'));
            gameContainer.appendChild(header);

            const selectLabel = document.createElement('label');
            selectLabel.setAttribute('for', 'gametype');
            selectLabel.className = 'input-select-label'
            selectLabel.innerHTML = 'Le type de partie :';
            const select = document.createElement('select');
            select.id = 'gametype';
            select.name = 'gametype';
            select.className = 'input-select'
            select.required = true;

            data.gameTypes.forEach(gameType => {
                const option = document.createElement('option');
                option.value = gameType.name;
                option.appendChild(document.createTextNode(gameType.name));
                select.appendChild(option);
            });

            gameContainer.appendChild(selectLabel);
            gameContainer.appendChild(select);

            const gameRolesContainer = document.createElement('div');
            gameRolesContainer.id = 'gameroles-container';
            gameRolesContainer.className = 'gameroles-container';
            gameContainer.appendChild(gameRolesContainer);

            const startGameButton = document.createElement('button');
            startGameButton.className = 'button';
            startGameButton.id = 'startgame-button';
            startGameButton.onclick = startGame;
            if (data.userList.length < 5) {
                startGameButton.disabled = true;
            }
            startGameButton.appendChild(document.createTextNode('Commencer la partie'));

            gameContainer.appendChild(startGameButton);

            setGameType(select.value);
        }

    }

    userList(data) {
        const userList = document.getElementById('user-list');
        userList.innerHTML = '';
        data.userList.forEach( user => {
                const newEl = document.createElement('div');
                let newElClass = 'user';

                if (user.nickname === this.session.nickname) {
                    newElClass += ' you';
                }
                if (user.admin != 0) {
                    newElClass += ' admin';
                    const adminEl = document.createElement('img');
                    adminEl.className = 'admin';
                    adminEl.src = baseUrl + '/resources/img/icon.png';
                    newEl.appendChild(adminEl);
                } else {
                    const adminEl = document.createElement('span');
                    adminEl.className = 'admin';
                    newEl.appendChild(adminEl);
                }

                newEl.className = newElClass;

                const nicknameEl = document.createElement('p');
                nicknameEl.className = 'nickname';
                nicknameEl.appendChild(document.createTextNode(user.nickname));
                newEl.appendChild(nicknameEl);

                const pointsEl = document.createElement('p');
                pointsEl.className = 'points';
                pointsEl.appendChild(document.createTextNode(user.points + ' pts'));
                newEl.appendChild(pointsEl);

                if (this.session.admin === 'true' && user.id && user.nickname !== this.session.nickname) {
                    const kickButton = document.createElement('button');
                    kickButton.className = 'button cancel';
                    kickButton.setAttribute('sessionId', user.id);
                    kickButton.onclick = kickSession;
                    kickButton.appendChild(document.createTextNode('Ratio'));

                    newEl.appendChild(kickButton);
                }
            userList.appendChild(newEl);
        });

        this.updateStartButtonStatus(data);
    }

    updateStartButtonStatus(data) {
        const startGameButton = document.getElementById('startgame-button');
        if (!startGameButton) {
            return;
        }
        if (data.userList.length < 5) {
            startGameButton.disabled = true;
        } else {
            startGameButton.disabled = false;
        }
    }
}

function setGameTypeEvent(e) {
    setGameType(e.target.value);
}

function setGameType(gameTypeName) {
    let gameType = null;

    const container = document.getElementById('gameroles-container');

    gameTypeData.forEach(
        gameTypeSex => {
            if(gameTypeSex.name === gameTypeName) {
                gameType = gameTypeSex;
            }
        }
    );

    if (!gameType || !container) {
        return;
    }

    container.innerHTML = '';

    const categorieContainer = document.createElement('div');
    categorieContainer.className = 'categorie-container';

    container.appendChild(categorieContainer);

    const rolesContainer = document.createElement('div');
    rolesContainer.className = 'roles-container';

    container.appendChild(rolesContainer);

    gameType.roles.forEach(role => {
        let categorieButton = document.getElementById(role.categorie + '-cat-button');

        if (!categorieButton) {
            categorieButton = document.createElement('button');
            categorieButton.id = role.categorie + '-cat-button';
            categorieButton.className = 'cat-button'
            categorieButton.setAttribute('role', role.categorie);
            categorieButton.appendChild(document.createTextNode(role.categorie));

            categorieContainer.appendChild(categorieButton);
        };

        let categorieRoles = document.getElementById(role.categorie + '-cat-roles');

        if (!categorieRoles) {
            categorieRoles = document.createElement('div');
            categorieRoles.id = role.categorie + '-cat-roles';
            categorieRoles.className = 'cat-roles';

            rolesContainer.appendChild(categorieRoles);
        };

        const roleSelect = document.createElement('p');
        roleSelect.innerHTML = role.name;

        categorieRoles.appendChild(roleSelect);
    });
}

function kickSession(e) {
    const sessionId = e.target.getAttribute('sessionId');
    api.kickSession(sessionId);
    e.target.disabled = true;
}

function startGame(e) {
    api.startGame();
    e.target.disabled = true;
}

export const lobbyPage = new LobbyPage();