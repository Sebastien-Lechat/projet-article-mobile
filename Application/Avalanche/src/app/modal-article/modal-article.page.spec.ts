import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { ModalArticlePage } from './modal-article.page';

describe('ModalArticlePage', () => {
  let component: ModalArticlePage;
  let fixture: ComponentFixture<ModalArticlePage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ModalArticlePage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(ModalArticlePage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
