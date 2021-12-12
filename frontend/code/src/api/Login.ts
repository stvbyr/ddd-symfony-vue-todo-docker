const domain = 'http://localhost:8080';

type User = {
    username: string;
    password: string;
};

function fetchToken(user: User) {
    return fetch(`${domain}/login_check`, {
        method: 'POST',
        body: JSON.stringify(user),
    });
}

export default { fetchToken };
