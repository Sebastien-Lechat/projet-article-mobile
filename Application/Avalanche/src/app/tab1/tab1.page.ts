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

  private articleListe

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
    return await modal.present();
  }

  public async showArticle () {
    this.article.getArticle().subscribe(data => {
      this.articleListe = data["articles"]
      // this.articleListe.forEach(article => {
      //   article.img = "https://pic.clubic.com/v1/images/1755972/raw?width=1200&fit=max&hash=63771b7ec65f73fb39d1696242a2ef9cdf042567"
      // })
    })
  }
}
