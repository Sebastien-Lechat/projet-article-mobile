import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
    providedIn: 'root'
})
export class FluxRSSService {

    constructor(
        private http: HttpClient
    ) {}

    getAllFlux() {
        return this.http.get('http://localhost:3000/flux')
    }

    getOneFlux(flux: string) {
        return this.http.get(flux, { responseType: 'text' })
    }
}