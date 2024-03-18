
module.exports = {
    modules: () => {

        return "hallo Wolrd";
    },
    getEnv: () => {
        return process.env
    },
    getEnvValue: (key) => {
        return process.env[key]
    }
}