import axios from 'axios';

// initial state
const state = {
    user: null
};

// getters
const getters = {
    isAuthenticated: state => !!state.user, // !! convert object to Boolean (If == 0, null, undefined) => false  ---> else => true
};

// actions
const actions = {
    async LogIn({commit}, User) {
        await axios.post('login', User)
        await commit('setUser', User.get('username'))
    },
};

// mutations
const mutations = {
    setUser(state, username){
        state.user = username
    },
};



// ---------------

export default {
    state,
    mutations,
    actions,
    getters
};
