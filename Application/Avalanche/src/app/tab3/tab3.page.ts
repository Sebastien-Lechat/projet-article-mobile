import { Component, OnInit } from '@angular/core';
import { FluxRSSService } from '../service/flux-rss.service';

@Component({
    selector: 'app-tab3',
    templateUrl: 'tab3.page.html',
    styleUrls: ['tab3.page.scss']
})
export class Tab3Page implements OnInit {

    private allArticle = []
    private count: number = 0
    private articleTitle: string
    private articleDescription: string
    private articleDate: Date
    private data = ["https://www.01net.com/rss/actualites/jeux/", "https://www.mamytwink.com/feed.xml", "https://api.20min.ch/rss/ro/view/137", "01 Net Jeux", "Mamytwink", "20 Minutes"]

    constructor(
        private flux: FluxRSSService
    ) {}

    ngOnInit() {
        this.showFlux()
    }

    private doRefresh(event) {
        this.allArticle = []
        this.showFlux()
        setTimeout(() => {
            console.log('Async operation has ended');
            event.target.complete();
        }, 2000);
    }

    public async showFlux() {
        this.flux.getAllFlux().subscribe(data => {
            for (let j = 0; j < 3; j++) {
                this.flux.getOneFlux(this.data[j]).subscribe(result => {
                    // this.flux.getOneFlux(data["flux"][j].link).subscribe(result => {
                    let parser = new DOMParser();
                    let xmlDoc = parser.parseFromString(result, "text/xml");
                    for (let i = 1; i < xmlDoc.getElementsByTagName("item").length; i++) {
                        if (xmlDoc.getElementsByTagName("title")[i].innerHTML.substr(0, 9) == "<![CDATA[") {
                            this.articleTitle = xmlDoc.getElementsByTagName("title")[i].innerHTML.slice(9, -3)
                        } else {
                            this.articleTitle = xmlDoc.getElementsByTagName("title")[i].innerHTML
                        }
                        if (xmlDoc.getElementsByTagName("description")[i].innerHTML.substr(0, 9) == "<![CDATA[") {
                            this.articleDescription = xmlDoc.getElementsByTagName("description")[i].innerHTML.slice(9, -3)
                        } else if (xmlDoc.getElementsByTagName("description")[i].innerHTML.substr(0, 1).match(/[^ \w]/)) {
                            this.articleDescription = ""
                        } else {
                            this.articleDescription = xmlDoc.getElementsByTagName("description")[i].innerHTML
                        }
                        this.articleDate = new Date(xmlDoc.getElementsByTagName("pubDate")[i].innerHTML)
                        this.allArticle[this.count] = {
                            "fluxTitle": data["flux"][j].name,
                            // "fluxTitle": this.data[j + 3],
                            "title": this.articleTitle,
                            "pubDate": this.articleDate,
                            "date": this.articleDate.toISOString().slice(8, 10) + "-" + this.articleDate.toISOString().slice(5, 7) + "-" + this.articleDate.toISOString().slice(0, 4) + " " + this.articleDate.toTimeString().slice(0, 8),
                            "description": this.articleDescription,
                            "link": xmlDoc.getElementsByTagName("link")[i].innerHTML
                        }
                        this.count += 1
                    }
                    this.allArticle = this.allArticle.sort(this.comp)
                })
            }
        })
    }

    private comp(a, b) {
        return new Date(b.pubDate).getTime() - new Date(a.pubDate).getTime();
    }
}