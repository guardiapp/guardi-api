class Param {
    param:string;
    value: string | boolean | number;
}

export class HttpParams {
    params = [];
    constructor () {}

    /**
     *
     * @param {Param[]} params
     */
    append(...params) {
        this.params = [...this.params, ...params];
    }

    /**
     *
     * @param {string} key
     */
    delete(key: string) {
        const param = this.params.find((param) => param.param == key);
        const indexOf = this.params.indexOf(param);
        this.params.splice(indexOf, 1);
    }

    /**
     *
     * @returns {string}
     */
    toString(): string {
        return this.params.map((param) => `${param.param}=${param.value}`).join("&");
    }

}
