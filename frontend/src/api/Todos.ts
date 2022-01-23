const domain = 'http://localhost:8080';

type NewTodo = {
    title: string;
    date: string;
};

function fetchAllTodos() {
    return fetch(`${domain}/todos`);
}

function createTodo(newTodo: NewTodo) {
    fetch(`${domain}/todo/create`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(newTodo),
    });
}

export default { fetchAllTodos, createTodo };
