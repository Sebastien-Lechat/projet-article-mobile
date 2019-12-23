import { Component, OnInit } from '@angular/core';
import { ArticleService } from '../service/article.service';
import { ModalController } from '@ionic/angular';

import { ModalArticlePage } from '../modal-article/modal-article.page';


@Component({
  selector: 'app-tab1',
  templateUrl: 'tab1.page.html',
  styleUrls: ['tab1.page.scss']
})
export class Tab1Page implements OnInit {

  private articleListe = []

  constructor(
    private article: ArticleService,
    private modalController: ModalController,
  ) {
  }

  ngOnInit() {
    this.showArticle ()
  }

  public async presentModal(idArticle) {
    const modal = await this.modalController.create({
      component: ModalArticlePage,
      componentProps: {
        'id': idArticle
      }
    });
    return await modal.present()
  }

  private doRefresh(event) {
    this.articleListe = []
    this.showArticle ()
    setTimeout(() => {
        console.log('Async operation has ended')
        event.target.complete()
    }, 2000)
}

  public async showArticle () {
    this.article.getArticle().subscribe(data => {
      this.articleListe = data["articles"]
      this.articleListe.forEach(article => {
        article.update_at = article.update_at.slice(8, 10) + "-" + article.update_at.slice(5, 7) + "-" + article.update_at.slice(0, 4) + " " + article.update_at.slice(11, 19)
      })
    })
  }
}
