import { Component, OnInit } from '@angular/core';
import { ModalController, NavParams } from '@ionic/angular';
import { ArticleService } from '../service/article.service';

@Component({
  selector: 'app-modal-article',
  templateUrl: './modal-article.page.html',
  styleUrls: ['./modal-article.page.scss'],
})
export class ModalArticlePage implements OnInit {

  private articleDetail

  constructor(
    private modalController: ModalController,
    private article: ArticleService,
    private navParams: NavParams,
    ) { }

  ngOnInit() {
    this.showArticle ()
  }

  private dismiss() {
    this.modalController.dismiss({
      'dismissed': true
    });
  }

  private async showArticle () {
    this.article.getOneArticle(this.navParams.get('id')).subscribe(data => {
      this.articleDetail = data["articles"]
      // this.articleListe.forEach(article => {
      //   article.img = "https://pic.clubic.com/v1/images/1755972/raw?width=1200&fit=max&hash=63771b7ec65f73fb39d1696242a2ef9cdf042567"
      // })
    })
  }
}
