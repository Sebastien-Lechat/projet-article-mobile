import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';


@Injectable({
    providedIn: 'root'
})
export class ArticleService {

    constructor(
        private http: HttpClient
    ) {}

    getArticle() {
        return this.http.get('http://localhost:3000/article')
    }

    getOneArticle(id) {
        return this.http.get('http://localhost:3000/article/' + id)
    }
}